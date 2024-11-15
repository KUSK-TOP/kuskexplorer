<?php
/**
 * @var \App\View\AppView $this
 */
use Cake\I18n\Number;

    $name = 'Block';
    $this->assign('title', __('Block').' - ');
    $lang=$this->request->getParam('lang');
?>
        <main>
            <div class="container px-4 py-1">
                <?php if ($block): ?>
                    <div class="row">
                        <div class="col">
                            <div class="card text-bg-light mb-2">
                                <div class="card-header fw-bold"><i class="bi bi-box mx-1"></i><?= __('Block') ?></div>
                                <div class="card-body">
                                    <dl>
                                        <dt><?= __('Number') ?></dt>
                                        <dd class="text-break"><?= h(Number::format($block->Height)) ?></dd>
                                        <dt><?= __('Hash') ?></dt>
                                        <dd class="text-break"><?= h($block->Hash) ?></dd>
                                        <dt><?= __('Size') ?></dt>
                                        <dd class="text-break"><?= h(Number::format($block->Size)) ?></dd>
                                        <dt><?= __('Version') ?></dt>
                                        <dd class="text-break"><?= h(Number::format($block->Version)) ?></dd>
                                        <dt><?= __('Previous Block Hash') ?></dt>
                                        <dd class="text-break"><?= h($block->PreviousBlockHash) ?></dd>
                                        <dt><?= __('Timestamp') ?></dt>
                                        <dd class="text-break"><?= h(__('{0} UTC', $block->TimestampFormatted)) ?></dd>
                                        <dt><?= __('Nonce') ?></dt>
                                        <dd class="text-break"><?= h(Number::format($block->Nonce)) ?></dd>
                                        <dt><?= __('Bits') ?></dt>
                                        <dd class="text-break"><?= h(Number::format($block->Bits)) ?></dd>
                                        <dt><?= __('Difficulty') ?></dt>
                                        <dd class="text-break"><?= h(Number::format($block->Difficulty)) ?></dd>
                                        <dt><?= __('Transaction Merkle Root') ?></dt>
                                        <dd class="text-break"><?= h($block->TransactionMerkleRoot) ?></dd>
                                        <dt><?= __('Transaction Status Hash') ?></dt>
                                        <dd class="text-break"><?= h($block->TransactionStatusHash) ?></dd>
                                        <dt><?= __('Transactions') ?></dt>
                                        <dd class="text-break"><?= $this->Html->link(
                                                __n(
                                                    '{0} Transaction', 
                                                    '{0} Transactions', 
                                                    count($block->Transactions),
                                                    count($block->Transactions)
                                                ),
                                                [
                                                    'controller'=>'Explorer',
                                                    'action'=>'blocktransactions',
                                                    'lang'=>$lang,
                                                    $block->Height
                                                ]
                                            ) ?></dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                <p><?= __('While using {0} an error occurred.', [$api_host]) ?></p>
                <?php endif; ?>
            </div>
        </main>
