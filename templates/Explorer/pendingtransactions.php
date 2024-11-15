<?php
/**
 * @var \App\View\AppView $this
 * @var \KUSK\API\PendingTransactions $transactions
 * @var \KUSK\API\PendingTransaction $transaction
 */
$name = 'Pending Transactions';
    $this->assign('title', __('Pending Transactions').' - ');
    $lang=$this->request->getParam('lang');
?>
        <main>
            <div class="container px-4 py-1">
                <?php if (isset($transactions)): ?>
                    <div class="row">
                        <div class="col">
                            <div class="card text-bg-light mb-2">
                                <div class="card-header fw-bold"><i class="bi bi-activity mx-1"></i><?= __('Pending Transactions') ?></div>
                                <div class="card-body">
                                    <?php if ($transactions->Total>0): ?>
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th><?= __('Transaction') ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($transactions->Transactions as $transaction): ?>
                                                <tr>
                                                    <td><?= $this->Html->link(
                                                        $transaction,
                                                        ['controller'=>'Explorer', 'action'=>'pendingtransaction', 'lang'=>$lang, $transaction]
                                                    ) ?></td>
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
                <p><?= __('No pending transactions.') ?></p>
                <?php endif; ?>
                <?php else: ?>
                <p><?= __('While using {0} an error occurred.', [$api_host]) ?></p>
                <?php endif; ?>
            </div>
        </main>
