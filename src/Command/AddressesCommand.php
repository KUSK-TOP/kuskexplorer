<?php
declare(strict_types=1);

namespace App\Command;

use App\Model\Table\AddressesTable;
use App\Model\Table\DataTable;
use App\Model\Table\TransfersTable;
use App\Model\Table\AssetsTable;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;
use Cake\Filesystem\File;
use Cake\I18n\Number;

use KUSK\API;
use KUSK\API\BlockTransaction;

/**
 * Addresses command.
 */
class AddressesCommand extends Command
{
    private API $api;
    private DataTable $Data;
    private AddressesTable $Addresses;
    private TransfersTable $Transfers;
    private AssetsTable $Assets;

    private string $KUSKAsset = 'ffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffff';

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code.
     *
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();

        $nodes = Configure::read('RPC');

        $this->api = new API(
            $nodes[0]['host'], 
            $nodes[0]['access-token']
        );

        $this->Data = $this->fetchTable('Data');
        $this->Addresses = $this->fetchTable('Addresses');
        $this->Transfers = $this->fetchTable('Transfers');
        $this->Assets = $this->fetchTable('Assets');
    }

    /**
     * Hook method for defining this command's option parser.
     *
     * @see https://book.cakephp.org/4/en/console-commands/commands.html#defining-arguments-and-options
     * @param \Cake\Console\ConsoleOptionParser $parser The parser to be defined
     * @return \Cake\Console\ConsoleOptionParser The built parser.
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser = parent::buildOptionParser($parser);

        $parser->addOption('reset-tables', [
            'short' => 'r',
            'help' => 'Reset the tables to the beginning',
            'boolean' => true,
        ]);

        return $parser;
    }

    /**
     * Implement this method with your command's logic.
     *
     * @param \Cake\Console\Arguments $args The command arguments.
     * @param \Cake\Console\ConsoleIo $io The console io
     * @return null|void|int The exit code or null for success
     */
    public function execute(Arguments $args, ConsoleIo $io)
    {
        if ($this->_lock($io)) {

            $reset = $args->getOption('reset-tables');

            if ($reset) {
                $this->_resetTables();
            }

            // Get last block from database
            $lastBlock = $this->Data->getLastBlock();
            $io->info(sprintf("Last Block: %s", Number::format($lastBlock)));

            // Get the current block
            $currentBlock = $this->api->getBlockCount();
            $io->info(sprintf("Current Block: %s", Number::format($currentBlock)));

            if ($lastBlock == 0) {
                $lastBlock--;
            }

            // Do the work
            if ($currentBlock != $lastBlock) {
                for ($blockNumber = $lastBlock + 1; $blockNumber <= $currentBlock; $blockNumber++) {
                    $io->info(sprintf("Processing block %d", $blockNumber));


                    $block = $this->api->getBlock($blockNumber);
                    if ($blockNumber > 0) {
                        $parentBlock = $this->api->getBlock(-1, $block->PreviousBlockHash);
                        if (is_null($parentBlock)) {
                            $io->info(sprintf('Block %d is orphaned', $blockNumber));
                            continue;
                        }
                    }
                    foreach ($block->Transactions as $transaction) {
                        if ($transaction->StatusFail) {
                            $io->info(sprintf('Block(%d): Transaction "%s" has failed', $blockNumber, $transaction->ID));
                            if ($transaction->Inputs[0]->Type != 'issue') {
                                continue;
                            }
                        }
                        $io->info("Transaction({$transaction->ID}): {$transaction->Inputs[0]->Type}");
                        $transactionType = $transaction->Inputs[0]->Type;
                        switch ($transactionType) {
                            case "coinbase":
                                $this->_coinbase($io, $blockNumber, $transaction);
                                break;
                            case "spend":
                                $this->_spend($io, $blockNumber, $transaction);
                                break;
                            case "issue":
                                $this->_issue($io, $blockNumber, $transaction);
                                break;
                            default:
                                $io->abort(sprintf(
                                    "Unknown transaction type %s",
                                    $transactionType
                                ));
                                break;
                        }
                    }

                    // Set the last block
                    if ($this->Data->setLastBlock($blockNumber)) {
                        $io->info("Updated last block to {$blockNumber}");
                    } else {
                        $io->abort("Could not update last block");
                    }
                }
                $this->_unlock($io);
            } else {
                $this->_unlock($io);
                $io->info("Nothing to do");
                return static::CODE_SUCCESS;
            }

        } else {
            $io->info('LOCKED: Exiting');
        }
    }

    /**
     * Attempts to lock
     *
     * @param ConsoleIo $io The IO for the console
     * @return bool
     */
    private function _lock(ConsoleIo $io): bool {
        //$io->info('LOCKING');
        $file = new File(TMP . 'addresses.lock', false);
        if (!file_exists(TMP . 'addresses.lock')) {
            file_put_contents(TMP .'addresses.lock', '');
            //$io->info('LOCKED');
            return true;
        } else {
            //$io->info('CANNOT LOCK');
            return false;
        }
    }

    /**
     * Attempts to unlock
     *
     * @param ConsoleIo $io The IO for the console
     * @return bool
     */
    private function _unlock(ConsoleIo $io): bool {
        //$io->info('UNLOCKING');
        return unlink(TMP . 'addresses.lock');
    }

    /**
     * Reset the tables
     *
     * @return void
     */
    private function _resetTables(): void {
        $connection = ConnectionManager::get('default');

        $connection->execute("TRUNCATE TABLE `addresses`");

        $connection->execute("TRUNCATE TABLE `assets`");
        $Assets = $this->fetchTable('assets');
        $Assets->createAsset(
            $this->KUSKAsset,
            'KUSK',
            0,
            '0000000000000000000000000000000000000000000000000000000000000000',
            'Kusk Official Issue',
            8,
            'KUSK',
            'KUSK',
            true
        );

        $connection->execute("TRUNCATE TABLE `data`");
        $Data = $this->fetchTable('data');
        $lastBlock = $Data->newEmptyEntity();
        $lastBlock->key = 'last_block';
        $lastBlock->value = 0;
        $minedBlocks = $Data->newEmptyEntity();
        $minedBlocks->key = 'mined_coins';
        $minedBlocks->value = 0;
        $Data
            ->saveMany([
                $lastBlock, 
                $minedBlocks
            ])
        ;

        $connection->execute("TRUNCATE TABLE `transfers`");
    }

    /**
     * Processes `coinbase` transactions
     * 
     * @param \Cake\Console\ConsoleIo $io The console Input/Output
     * @param int $blockNumber The block number of the transaction
     * @param \KUSK\API\BlockTransaction $transaction The transaction to process
     * @return int
     */
    private function _coinbase(ConsoleIo $io, int $blockNumber, BlockTransaction $transaction ): void {
        $transactionHash = $transaction->ID;
        $from = 'coinbase';
        $to = $transaction->Outputs[0]->Address;
        $amount = $transaction->Outputs[0]->Amount;

        if (empty($to)) {
            $to = 'BLANK OR ERROR';
        }

        // Transfer
        if ($this->Transfers->createTransfer(
            $blockNumber, 
            $transactionHash, 
            $from, 
            $this->KUSKAsset, 
            $to, 
            $this->KUSKAsset,
            $amount)) {
            $io->success("    COINBASE: Created block reward transfer");
        } else {
            $io->abort(sprintf("COINBASE: Error saving transfer on block %d", $blockNumber));
        }

        // Address
        if ($this->Addresses->credit($to, $amount, $this->KUSKAsset)) {
            $io->success(sprintf("    COINBASE: Credited %s to address %s", Number::format($amount/100000000), $to));
        } else {
            $io->abort(sprintf("COINBASE: Error crediting to address balance %s", $to));
        }

        // Mined Coins
        if ($this->Data->addMined($amount)) {
            $io->success(sprintf("    COINBASE: Credited %s to mined coins", Number::format($amount/100000000)));
        } else {
            $io->abort("COINBASE: Error crediting to mined coins");
        }
    }

    /**
     * Processes `spend` transactions
     * 
     * @param \Cake\Console\ConsoleIo $io The console Input/Output
     * @param int $blockNumber The block number of the transaction
     * @param \KUSK\API\BlockTransaction $transaction The transaction to process
     * @return int
     */
    private function _spend(ConsoleIo $io, int $blockNumber, BlockTransaction $transaction ): void {
        $transactionHash = $transaction->ID;

        // Debit
        foreach ($transaction->Inputs as $input) {
            $address = $input->Address;
            $amount = $input->Amount;
            $asset = $input->AssetID;
            if ($this->Addresses->debit($address, $amount, $asset)) {
                $io->success(sprintf(
                    "    SPEND: Debited %s to address %s", 
                    Number::format($amount/100000000), 
                    $address
                ));
            } else {
                $io->abort(sprintf("SPEND: Error debiting to address balance %s", $address));
            }
        }

        // Credit
        foreach ($transaction->Outputs as $output) {
            $address = $output->Address;
            $amount = $output->Amount;
            $asset = $output->AssetID;
            if ($this->Addresses->credit($address, $amount, $asset)) {
                $io->success(sprintf(
                    "    SPEND: Credited %s to address %s", 
                    Number::format($amount/100000000), 
                    $address
                ));
            } else {
                $io->abort(sprintf("SPEND: Error crediting to address balance %s", $address));
            }
        }

        $from = $transaction->Inputs[0]->Address;
        $source_asset = $transaction->Inputs[0]->AssetID;
        foreach ($transaction->Outputs as $output) {
            if ($this->Transfers->createTransfer(
                $blockNumber, 
                $transactionHash, 
                $from, 
                $source_asset,
                $output->Address, 
                $output->AssetID,
                $output->Amount,
            )) {
                $io->success(sprintf(
                    "    SPEND: Created transfer from %s to %s of %s",
                    $from,
                    $output->Address,
                    Number::format($output->Amount/100000000),
                ));
            } else {
                $io->abort(sprintf(
                    "SPEND: Error creating transfer from %s to %s of %s",
                    $from,
                    $output->Address,
                    Number::format($output->Amount/100000000),
                ));
            }
        }
    }

    /**
     * Processes `issue` transactions
     * 
     * @param \Cake\Console\ConsoleIo $io The console Input/Output
     * @param int $blockNumber The block number of the transaction
     * @param \KUSK\API\BlockTransaction $transaction The transaction to process
     * @return int
     */
    private function _issue(ConsoleIo $io, int $blockNumber, BlockTransaction $transaction ): void {
        //$transactionHash = $transaction->ID;

        $assetTransaction = $transaction->Inputs[0];
        $asset = $this->Assets->getAsset($assetTransaction->AssetID);
        $description = '';
        if ($asset) {
            if(!$this->Assets->updateAsset(
                $assetTransaction->AssetID,
                $assetTransaction->AssetAlias,
                $blockNumber,
                $transaction->ID,
                $description,
                $assetTransaction->AssetDefinition->Decimals,
                $assetTransaction->AssetDefinition->Name,
                $assetTransaction->AssetDefinition->Symbol,
                !$transaction->StatusFail
            )){
                $io->abort("Could not update asset {$assetTransaction->AssetID}");
            };
        } else {
            switch (is_null($assetTransaction->AssetDefinition->Description)) {
                case true:
                    $description = 'Desc is null';
                    break;
                case false:
                    $io->info("    ISSUE: " . json_encode($assetTransaction->AssetDefinition->Description));
                    $description = '';
                    foreach ($assetTransaction->AssetDefinition->Description as $key=>$value) {
                        $description .= "{$key}: {$value}";
                    }
                    break;
            }
            if(!$this->Assets->createAsset(
                $assetTransaction->AssetID,
                $assetTransaction->AssetAlias,
                $blockNumber,
                $transaction->ID,
                $description,
                $assetTransaction->AssetDefinition->Decimals,
                $assetTransaction->AssetDefinition->Name,
                $assetTransaction->AssetDefinition->Symbol,
                !$transaction->StatusFail
            )){
                $io->abort("Could not create asset {$assetTransaction->AssetID}");
            };
        }

        // Debit
        foreach ($transaction->Inputs as $input) {
            $address = $input->Address;
            $amount = $input->Amount;
            $asset = $input->AssetID;
            if ($input->Type === "issue") {
                continue;
            }
            if ($this->Addresses->debit($address, $amount, $asset)) {
                $io->success(sprintf(
                    "    ISSUE: Debited %s to address %s", 
                    Number::format($amount/100000000), 
                    $address
                ));
            } else {
                $io->abort(sprintf("ISSUE: Error debiting to address balance %s", $address));
            }
        }

        // Credit
        foreach ($transaction->Outputs as $output) {
            $address = $output->Address;
            $amount = $output->Amount;
            $asset = $output->AssetID;
            if ($this->Addresses->credit($address, $amount, $asset)) {
                $io->success(sprintf(
                    "    ISSUE: Credited %s to address %s", 
                    Number::format($amount/100000000), 
                    $address
                ));
            } else {
                $io->abort(sprintf("ISSUE: Error crediting to address balance %s", $address));
            }
        }

        // Transfers
        // TODO: Make sure a transfer as been made from the creation of the new coin.
    }
}
