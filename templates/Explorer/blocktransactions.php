<?php
/**
 * @var \App\View\AppView $this
 * @var \KUSK\API\BlockTransaction $transaction
 */
use Cake\I18n\Number;

    $name = 'Block Transactions';
    $this->assign('title', __('Block Transactions').' - ');
    $lang=$this->request->getParam('lang');
?>
        <main>
            <div class="container px-4 py-1">
                <?php if (isset($transactions)): ?>
                    <div class="row">
                        <div class="col">
                            <div class="card text-bg-light mb-2">
                                <div class="card-header fw-bold"><i class="bi bi-cash-stack mx-1"></i><?= __('Block Transactions') ?></div>
                                <div class="card-body">
                                    <p><?= __('Block') ?>: <?= $this->Html->link(
                                        Number::format(intval($this->request->getParam('pass')[0])),
                                        [
                                            'controller'=>'Explorer',
                                            'action'=>'block',
                                            'lang'=>$lang,
                                            $this->request->getParam('pass')[0]
                                        ]
                                    ) ?></p>
                                    <?php if (count($transactions)>0): ?>
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th><?= __('Transaction') ?></th>
                                                    <th><?= __('Status') ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($transactions as $transaction): ?>
                                                <tr>
                                                    <td><?= $this->Html->link(
                                                        $transaction->ID,
                                                        [
                                                            'controller'=>'Explorer', 
                                                            'action'=>'transaction', 
                                                            'lang'=>$lang, 
                                                            $transaction->ID
                                                        ]
                                                    ) ?></td>
                                                    <td><?= ($transaction->StatusFail)?'<i class="bi bi-x-circle text-danger mx-1"></i>':'<i class="bi bi-check-circle text-success mx-1"></i>' ?></td>
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
                <p><?= __('No transactions on this block') ?></p>
                <?php endif; ?>
                <?php else: ?>
                <p><?= __('While using {0} an error occurred.', [$api_host]) ?></p>
                <?php endif; ?>
            </div>
        </main>
