<?php
/**
 * @var \App\View\AppView $this
 */
use Cake\I18n\Number;

    $name = 'Top 100';
    $this->assign('title', __('Top 100').' - ');
    $lang=$this->request->getParam('lang');
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

                <?php if ($addresses): ?>
                <div class="row">
                    <div class="col">
                        <div class="card text-bg-light mb-2">
                            <div class="card-header fw-bold"><i class="bi bi-sort-down mx-1"></i><?= __('Top 100') ?></div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-triped">
                                        <thead>
                                            <tr>
                                                <th><?= __('Rank') ?></th>
                                                <th><?= __('Address') ?></th>
                                                <th><?= __('Balance') ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($addresses as $index => $address): ?>
                                            <tr>
                                            <td><?= h($index + 1) ?></td>
                                            <?php if ($address->address == 'ey1qqemcgcj69kqhfw2ewj80f7cydmy0uwwwd9vck3'): ?>
                                            <td><?= $this->Html->link(
                                                'Project Funds',
                                                ['controller'=>'Explorer', 'action'=>'address', 'lang'=>$lang, $address->address]
                                            ) ?> <i class="bi bi-lock-fill"></i></td>
                                            <?php else: ?>
                                            <td><?= $this->Html->link(
                                                $address->address,
                                                ['controller'=>'Explorer', 'action'=>'address', 'lang'=>$lang, $address->address]
                                            ) ?></td>
                                            <?php endif; ?>
                                            <td><?= h(Number::format($address->balance/100000000, ['places'=> $ey_asset->asset_definition_decimals]) . " {$ey_asset->asset_definition_name}") ?></td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php else: ?>
                <p><?= __('While using {0} an error occurred.', [$api_host]) ?></p>
                <?php endif; ?>
            </div>
        </main>
