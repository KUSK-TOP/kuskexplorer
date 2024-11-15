<?php
/**
 * @var \App\View\AppView $this
 */
use Cake\I18n\Number;

    $name = 'Address';
    $this->assign('title', __('Address').' - ');
    $lang=$this->request->getParam('lang');
    $debug = false;
?>
        <main>
            <div class="container px-4 py-1">

                <!-- <div class="container px-4 pt-2">
                    <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
                    <symbol id="exclamation-triangle-fill" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                    </symbol>
                    </svg>
                    <div class="alert alert-warning d-flex align-items-center" role="alert">
                        <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Warning:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                        <div>
                            Please be aware that some transfers may be missing, but the balances should be accurate.<br>
                            Many features have been implemented and we felt that some missing transfers would not be an immediate issue.<br>
                            Please alert the team on Discord or Telegram if you find a discrepancy on the balances, and on the balances only.<br><br>
                            While support for other assets is being implemented, some of the addresses may have balances in different assets.
                        </div>
                    </div>
                </div> -->

                <?php if ($debug): ?>
                <div class="card text-bg-light mb-2">
                    <div class="card-header fw-bold"><i class="bi bi-bug mx-1"></i>Debug</div>
                    <div class="card-body">
                        <div><?php debug($assetList); ?></div>
                        <div><?php debug($balances); ?></div>
                    </div>
                </div>
                <?php endif; ?>
                <?php if ($address): ?>
                <div class="row">
                    <div class="col">
                        <div class="card text-bg-light mb-2">
                            <div class="card-header fw-bold"><i class="bi bi-handbag mx-1"></i><?= __('Address') ?>: <?=  $address->first()->address; ?></div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <dl>
                                            <dt class="h5">KUSK Balance</dt>
                                            <dd><?= Number::format(
                                                $balances['ffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffff']['balance'] / 100000000,
                                                ['places' => 8]
                                            ) . ' KUSK' ?></dd>
                                            <?php if ($address->first()->address == 'ey1qqemcgcj69kqhfw2ewj80f7cydmy0uwwwd9vck3'): ?>
                                            <dt class="h5">Project Funds</dt>
                                            <dd>Locked amount, Phased release</dd>
                                            <?php endif; ?>
                                        </dl>
                                    </div>
                                    <div class="col">
                                        <h1 class="h5">Tokens</h1>
                                        <?php if (count($balances) > 1): ?>
                                        <dl>
                                            <?php foreach ($balances as $assetID => $balance): ?>
                                            <?php if ( $assetID != 'ffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffff' ): ?>
                                            <dt><?= $assetList[$assetID]->status?'<i class="bi bi-check-circle text-success mx-1"></i>':'<i class="bi bi-x-circle text-danger mx-1"></i>' ?><?= $assetList[$assetID]->asset_definition_name ?> Balance</dt>
                                            <dd><?= Number::format(
                                                $balance['balance'] / 100000000,
                                                [
                                                    'places' => $assetList[$assetID]->asset_definition_decimals
                                                ]
                                                ) . " {$assetList[$assetID]->asset_definition_symbol}" ?></dd>
                                            <?php endif; ?>
                                            <?php endforeach; ?>
                                        </dl>
                                        <?php else: ?>
                                        <p>No token balance</p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php if (count($transfers)>0): ?>
                                <hr>
                                <h5><?= __('Most recent transfers ({0})', count($transfers)) ?></h5>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th><?= __('Block') ?></th>
                                                <th><?= __('Transaction') ?></th>
                                                <th><?= __('From') ?></th>
                                                <th><?= __('To') ?></th>
                                                <th><?= __('Amount') ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($transfers as $transfer): ?>
                                            <tr>
                                                <td><?= $this->Html->link(
                                                    Number::format($transfer->block_number),
                                                    [
                                                        'controller'=>'Explorer', 
                                                        'action'=>'block', 
                                                        'lang'=>$lang, 
                                                        $transfer->block_number,
                                                    ]
                                            ) ?></td>
                                                <td><?= $this->Html->link(
                                                    sprintf(
                                                        "%s...%s", 
                                                        substr($transfer->transaction_hash, 0,10),
                                                        substr($transfer->transaction_hash, -10)
                                                    ),
                                                    [
                                                        'controller'=>'Explorer', 
                                                        'action'=>'transaction', 
                                                        'lang'=>$lang, 
                                                        $transfer->transaction_hash,
                                                    ]
                                                ) ?></td>
                                                <td><?php
                                                    if($transfer->from == $address->first()->address || $transfer->from == 'coinbase') {
                                                        echo $transfer->from;
                                                    } else {
                                                        echo $this->Html->link(
                                                            sprintf(
                                                                "%s...%s", 
                                                                substr($transfer->from, 0,10),
                                                                substr($transfer->from, -10)
                                                            ),
                                                            [
                                                                'controller'=>'Explorer', 
                                                                'action'=>'address', 
                                                                'lang'=>$lang, 
                                                                $transfer->from,
                                                            ]
                                                        );
                                                    }
                                                ?></td>
                                                <td><?php
                                                    if($transfer->to == $address->first()->address || $transfer->to == 'coinbase') {
                                                        echo $transfer->to;
                                                    } else {
                                                        echo $this->Html->link(
                                                            sprintf(
                                                                "%s...%s", 
                                                                substr($transfer->to, 0,10),
                                                                substr($transfer->to, -10)
                                                            ),
                                                            [
                                                                'controller'=>'Explorer', 
                                                                'action'=>'address', 
                                                                'lang'=>$lang, 
                                                                $transfer->to,
                                                            ]
                                                        );
                                                    }
                                                ?></td>
                                                <td class="text-end"><?php
                                                    $amount  = $transfer->amount;
                                                    if ($transfer->from == $address->first()->address) {
                                                        $amount = -$amount;
                                                    }
                                                    echo Number::format($amount / 100000000);
                                                    echo ' ';
                                                    echo $assetList[$transfer->source_asset]->asset_definition_name;
                                                    echo ' -> ';
                                                    echo $assetList[$transfer->destination_asset]->asset_definition_name;
                                                ?></td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <?php else: ?>
                                <?= __('No transfers') ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php else: ?>
                <p><?= __('While using {0} an error occurred.', [$api_host]) ?></p>
                <?php endif; ?>
            </div>
        </main>
