<?php
declare(strict_types=1);

namespace App\Controller;

use KUSK\API;

use Cake\Core\Configure;
use Cake\I18n\I18n;
use Cake\I18n\Number;

/**
 * Explorer Controller
 *
 * @method \App\Model\Entity\Explorer[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ExplorerController extends AppController
{
    private API $api;
    private string $lang = 'en';
    private array $hashUnit = [
        "h/s",
        "Kh/s",
        "Mh/s",
        "Gh/s",
        "Th/s",
        "Ph/s",
    ];

    private array $numberUnit = [
        "",
        "K",
        "M",
        "G",
        "T",
        "P",
    ];

    private array $currencyUnit = [
        "",
        "Thousand",
        "Million",
        "Billion",
    ];
    private string $KUSKAsset = 'ffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffff';

    /**
     * Format Hash Rate
     * 
     * @param float $hashRate The hash rate to format
     * @return string
     */
    private function _FormatHashRate(float $hashRate): string {
        $u = 0;
        $h = $hashRate;

        while ($h >= 1024) {
            $u += 1;
            $h /= 1024;
        }

        $result = Number::format($h, [
            "precision" => 3,
            "after" => " " . $this->hashUnit[$u]
        ]);

        return $result;
    }

    /**
     * Format Currency
     * 
     * @param float $number The hash rate to format
     * @return string
     */
    private function _FormatCurrency(float $number): string {
        $u = 0;
        $n = $number;

        while ($n >= 1000) {
            $u += 1;
            $n /= 1000;
        }

        $result = Number::format($n, [
            "precision" => 2,
            "after" => " " . $this->currencyUnit[$u]
        ]);

        return $result;
    }

    /**
     * Format Number
     * 
     * @param float $number The hash rate to format
     * @return string
     */
    private function _FormatNumber(float $number): string {
        $u = 0;
        $n = $number;

        while ($n >= 1000) {
            $u += 1;
            $n /= 1000;
        }

        $result = Number::format($n, [
            "precision" => 3,
            "after" => " " . $this->numberUnit[$u]
        ]);

        return $result;
    }

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

        $lang = $this->request->getParam('lang');
        if (isset($lang)) {
            $this->lang = $lang;
            I18n::setLocale($lang);
        }
    }

    /**
     * Redirect method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function toen()
    {
        $domain = $this->request->getEnv('HTTP_HOST');
        if ($domain == 'explorer.kusk.top') {
            return $this->redirect("https://{$domain}/{$this->lang}", 301);
        } else {
            return $this->redirect(['controller'=>'Explorer', 'action'=>'index', 'lang'=>'en'], 301);
        }
        
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index($from = null)
    {
        $currentBlock = $this->api->getBlockCount();
        $pendingCount = $this->api->getPendingTransactions()->Total;
        if (empty($from)) {
            $blockFrom = $currentBlock;
        } else {
            if ((int)$from > $currentBlock) {
                $blockFrom = $currentBlock;
            } else {
                $blockFrom = (int)$from;
            }
        }
        $blocks = [];
        $miners = [];
        for ($i = $blockFrom; $i > $blockFrom - 10; $i--) {
            $block = $this->api->getBlock($i);
            $blocks[] = $block;
            $miners[] = $block->Transactions[0]->Outputs[0]->Address;
        }
        $hashRate = $this->api->getHashRate($blocks[0]->Height)->HashRate;
        $hashRate = $this->_FormatHashRate($hashRate);
        $difficulty = $this->_FormatNumber(intval($blocks[0]->Difficulty));

        if ($blockFrom == $currentBlock) {
            $previous = $blockFrom - 10;
            $next = null;
        } else {
            $previous = $blockFrom - 10;
            $next = $blockFrom + 10;
        }

        $mined = $this->_FormatCurrency($this->fetchTable('Data')->getMined() / 100000000);
        $supply = $this->_FormatCurrency(2100000000.00000000);
        $nodes = Configure::read('RPC');
        $api_host = $nodes[0]['host'];
        $this->set(compact(
            'api_host', 
            'currentBlock', 
            'pendingCount', 
            'blocks', 
            'hashRate', 
            'difficulty',
            'mined', 
            'miners',
            'supply', 
            'previous', 
            'next'
        ));
    }

    /**
     * Block method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function block($blockNumber = null)
    {
        if (isset($blockNumber)) {
            if (is_numeric($blockNumber) and intval($blockNumber >= 0)) {

                $block = $this->api->getBlock(intval($blockNumber));

                if (!isset($block)) {
                    $block = null;
                    $this->Flash->error(__('Need to provide a valid block'));
                }
            } else {
                $block = null;
                $this->Flash->error(__('Need to provide a valid block'));
            }
        } else {
            $block = null;
            $this->Flash->error(__('Need to provide a block number'));
        }

        $nodes = Configure::read('RPC');
        $api_host = $nodes[0]['host'];
        $this->set(compact('api_host', 'block'));
    }

    /**
     * Address method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function address($wallet = null)
    {
        $valid = $this->api->validateAddress($wallet);
        if ($valid->Valid) {
            $Addresses = $this->fetchTable('Addresses');
            $address = $Addresses->getAddress($wallet);

            $transfers = null;
            if (is_null($address)) {
                $address = null;
                $this->Flash->error(__('Need to provide a valid address, or the address is missing from our database'));
            } else {
                $Assets = $this->fetchTable('Assets');
                $assets = $Assets->getAssets();
                $assetList = [];
                foreach($assets as $asset) {
                    $assetList[$asset->asset] = $asset;
                }
                $balances =[];
                foreach ($address as $addr) {
                    if (isset($assetList[$addr->asset])) {
                        $balances[$addr->asset] = [
                            'balance' => $addr->balance,
                        ];
                    }
                }
                $Transfers = $this->fetchTable('Transfers');
                $transfers = $Transfers->getTransfers($wallet);
            }
        } else {
            $address = null;
            $balances = null;
            $transfers = null;
            $assetList = null;
            $this->Flash->error(__('Need to provide a valid address'));
        }

        $nodes = Configure::read('RPC');
        $api_host = $nodes[0]['host'];
        $this->set(compact('api_host', 'address', 'transfers', 'assetList', 'balances'));
    }

    /**
     * Transaction method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function transaction($transactionID = null)
    {
        if (isset($transactionID)) {
            if (strlen($transactionID) == 64) {
                $transaction = $this->api->getTransaction($transactionID);
                if (!isset($transaction)) {
                    $transaction = null;
                    $this->Flash->error(__('Need to provide a valid transaction'));
                } else {
                    foreach ($transaction->Inputs as $input) {
                        if ($input->Type != 'coinbase') {
                            $asset = $this->api->getAsset($input->AssetID);
                            $input->AssetAlias = $asset->Alias;
                            $input->AssetDefinition = $asset->Definition;
                        }
                    }
                    foreach ($transaction->Outputs as $output) {
                        $asset = $this->api->getAsset($output->AssetID);
                        $output->AssetAlias = $asset->Alias;
                        $output->AssetDefinition = $asset->Definition;
                    }
                }
            } else {
                $transaction = null;
                $this->Flash->error(__('Transaction hash is not correct'));
            }
        } else {
            $transaction = null;
            $this->Flash->error(__('Need to provide an transaction'));
        }

        $nodes = Configure::read('RPC');
        $api_host = $nodes[0]['host'];
        $this->set(compact('api_host', 'transaction'));
    }

    /**
     * Block Transactions method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function blocktransactions($blockNumber = null)
    {
        if (isset($blockNumber)) {
            if (is_numeric($blockNumber) and intval($blockNumber >= 0)) {
                $transactions = $this->api->getBlock(intval($blockNumber))->Transactions;

                if (!isset($transactions)) {
                    $transactions = null;
                    $this->Flash->error(__('Something went wrong while getting the transaction'));
                }
            } else {
                $transactions = null;
                $this->Flash->error(__('Need to provide a valid block'));
            }
        } else {
            $transactions = null;
            $this->Flash->error(__('Need to provide a block'));
        }

        $nodes = Configure::read('RPC');
        $api_host = $nodes[0]['host'];
        $this->set(compact('api_host', 'transactions'));
    }

    /**
     * Pending Transactions method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function pendingtransactions()
    {
        $transactions = $this->api->getPendingTransactions();

        if (!isset($transactions)) {
            $transactions = null;
            $this->Flash->error(__('Something went wrong while getting the pending transactions'));
        }

        $nodes = Configure::read('RPC');
        $api_host = $nodes[0]['host'];
        $this->set(compact('api_host', 'transactions'));
    }

    /**
     * Pending Transaction method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function pendingtransaction($hash = null)
    {
        if (isset($hash)) {
            if (strlen($hash) == 64) {
                $transaction = $this->api->getPendingTransaction($hash);
                if (!isset($transaction)) {
                    $domain = $this->request->getEnv('HTTP_HOST');
                    if ($domain == 'explorer.kusk.top') {
                        return $this->redirect("https://{$domain}/{$this->lang}/transaction/{$hash}");
                    } else {
                        return $this->redirect(['action' => 'transaction','lang'=> $this->lang, $hash]);
                    }
                } else {
                    foreach ($transaction->Inputs as $input) {
                        if ($input->Type != 'coinbase') {
                            $asset = $this->api->getAsset($input->AssetID);
                            $input->AssetAlias = $asset->Alias;
                            $input->AssetDefinition = $asset->Definition;
                        }
                    }
                    foreach ($transaction->Outputs as $output) {
                        $asset = $this->api->getAsset($output->AssetID);
                        $output->AssetAlias = $asset->Alias;
                        $output->AssetDefinition = $asset->Definition;
                    }
                }
            } else {
                $transaction = null;
                $this->Flash->error(__('Transaction hash is not correct'));
            }
        } else {
            $transaction = null;
            $this->Flash->error(__('Need to provide a pending transaction'));
        }


        $nodes = Configure::read('RPC');
        $api_host = $nodes[0]['host'];
        $this->set(compact('api_host', 'hash', 'transaction'));
    }

    /**
     * Assets method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function assets() {
        //$assets = $this->api->getAssets();
        $assetsTable = $this->fetchTable('Assets');
        $assets = $assetsTable->getAssets();

        $nodes = Configure::read('RPC');
        $api_host = $nodes[0]['host'];
        $this->set(compact('api_host', 'assets'));
    }

    /**
     * Asset method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function asset($assetID = null) {
        if (isset($assetID)) {

            $asset = $this->api->getAsset($assetID);
            if ($assetID != 'ffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffff') {
                $Assets = $this->fetchTable('Assets');
                $extra = $Assets->getAsset($assetID);
                $asset->Definition->Decimals = $extra->asset_definition_decimals;
                $asset->Definition->Name = $extra->asset_definition_name;
                $asset->Definition->Symbol = $extra->asset_definition_symbol;
                $asset->Status = $extra->status;
            }

        } else {

            $asset = null;
            $this->Flash->error(__('Need to provide an asset'));

        }
        
        $nodes = Configure::read('RPC');
        $api_host = $nodes[0]['host'];
        $this->set(compact('api_host', 'asset'));
    }

    /**
     * Top 100 addresses
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function top() {
        $Addresses = $this->fetchTable('Addresses');
        $addresses = $Addresses->top(100);
        $Assets = $this->fetchTable('Assets');
        $ey_asset = $Assets->getAsset($this->KUSKAsset);

        $nodes = Configure::read('RPC');
        $api_host = $nodes[0]['host'];
        $this->set(compact('api_host', 'addresses', 'ey_asset'));
    }

    /**
     * Search method
     *
     * @param string $query Should contain a block number, or a transaction hash, or an address
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function search($query = null)
    {
        $domain = $this->request->getEnv('HTTP_HOST');
        if (isset($query)) {
            if (is_numeric($query)) {

                $query = intval($query);
                if ($domain == 'explorer.kusk.top') {
                    return $this->redirect("https://{$domain}/{$this->lang}/block/{$query}");
                } else {
                    return $this->redirect(['action'=>'block', 'lang'=>$this->lang, $query]);
                }

            } elseif (substr($query, 0, 2) == 'ey') {

                if ($domain == 'explorer.kusk.top') {
                    return $this->redirect("https://{$domain}/{$this->lang}/address/{$query}");
                } else {
                    return $this->redirect(['action'=>'address', 'lang'=>$this->lang, $query]);
                }

            } else {

                if ($domain == 'explorer.kusk.top') {
                    return $this->redirect("https://{$domain}/{$this->lang}/pendingtransaction/{$query}");
                } else {
                    return $this->redirect(['action'=>'pendingtransaction', 'lang'=>$this->lang, $query]);
                }

            }
        } else {

            $this->Flash->error(__('Need to provide a query'));
            if ($domain == 'explorer.kusk.top') {
                return $this->redirect("https://{$domain}/{$this->lang}");
            } else {
                return $this->redirect(['action'=>'index', 'lang'=>$this->lang]);
            }

        }
    }
}
