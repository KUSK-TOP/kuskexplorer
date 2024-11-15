<?php
/**
 * @var \App\View\AppView $this
 * @var \KUSK\API\PendingTransaction $transaction
 */
use Cake\I18n\Number;

    $name = 'Pending Transaction';
    $this->assign('title', __('Pending Transaction').' - ');
    $lang=$this->request->getParam('lang');
    if (!is_null($transaction)) {
        $inputsCount = count($transaction->Inputs);
        $outputsCount = count($transaction->Outputs);
    }
?>
        <main>
            <div class="container px-4 py-1">
                <?php if (!is_null($transaction)): ?>
                <div class="row">
                    <div class="col">
                        <div class="card text-bg-light mb-2">
                            <div class="card-header fw-bold"><i class="bi bi-activity mx-1"></i><?= __('Pending Transaction') ?></div>
                            <div class="card-body">
                                <dl>
                                    <dt><?= __('ID') ?></dt>
                                    <dd class="text-break"><?= h($transaction->ID) ?></dd>
                                    <dt><?= __('Version') ?></dt>
                                    <dd class="text-break"><?= h(Number::format($transaction->Version)) ?></dd>
                                    <dt><?= __('Size') ?></dt>
                                    <dd class="text-break"><?= h(Number::format($transaction->Size)) ?></dd>
                                    <dt><?= __('Status') ?></dt>
                                    <dd class="text-break"><?= ($transaction->StatusFail)?'<i class="bi bi-x-circle text-danger mx-1"></i>':'<i class="bi bi-check-circle text-success mx-1"></i>' ?></dd>
                                    <dt><?= __('Mux ID') ?></dt>
                                    <dd class="text-break"><?= h($transaction->MuxID) ?></dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="card text-bg-light mb-2">
                            <div class="card-header fw-bold"><?= __n(
                                '{0} Input',
                                '{0} Inputs',
                                $inputsCount,
                                $inputsCount,
                            ) ?></div>
                            <div class="card-body">
                                <?php if ($inputsCount > 0): ?>
                                    <?php foreach ($transaction->Inputs as $input): ?>
                                    <dl>
                                        <dt><?= __('Type') ?></dt>
                                        <dd><?= h($input->Type) ?></dd>
                                        <dt><?= __('Asset') ?></dt>
                                        <dd><?= $this->Html->link(
                                            h($input->AssetAlias),
                                            [
                                                'controller'=>'Explorer',
                                                'action'=>'asset',
                                                'lang'=>$lang,
                                                $input->AssetID,
                                            ]
                                        ) ?></dd>
                                        <dt><?= __('Amount') ?></dt>
                                        <dd><?= h(Number::format(
                                            $input->Amount/100000000,
                                            [
                                                'places'=>8
                                            ]
                                        ) . " " . (($input->AssetAlias == 'KUSK')?$input->AssetAlias:'OTHER ASSET')) ?></dd>
                                        <dt><?= __('Address') ?></dt>
                                        <dd><?= $this->Html->link(
                                            h($input->Address),
                                            [
                                                'controller'=>'Explorer',
                                                'action'=>'address',
                                                'lang'=>$lang,
                                                $input->Address,
                                            ]
                                        ) ?></dd>
                                    </dl>
                                    <hr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                <?= __('No Inputs') ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="card text-bg-light mb-2">
                            <div class="card-header fw-bold"><?= __n(
                                '{0} Output',
                                '{0} Outputs',
                                $outputsCount,
                                $outputsCount,
                            ) ?></div>
                            <div class="card-body">
                                <?php if ($outputsCount > 0): ?>
                                    <?php foreach ($transaction->Outputs as $output): ?>
                                        <dl>
                                        <dt><?= __('Type') ?></dt>
                                        <dd><?= h($output->Type) ?></dd>
                                        <dt><?= __('ID') ?></dt>
                                        <dd><?= h($output->ID) ?></dd>
                                        <dt><?= __('Asset') ?></dt>
                                        <dd><?= $this->Html->link(
                                            h($output->AssetAlias),
                                            [
                                                'controller'=>'Explorer',
                                                'action'=>'asset',
                                                'lang'=>$lang,
                                                $output->AssetID,
                                            ]
                                        ) ?></dd>
                                        <dt><?= __('Amount') ?></dt>
                                        <dd><?= h(Number::format(
                                            $output->Amount/100000000,
                                            [
                                                'places'=>8
                                            ]
                                        ) . " " . (($output->AssetAlias == 'KUSK')?$output->AssetAlias:'OTHER ASSET')) ?></dd>
                                        <dt><?= __('Address') ?></dt>
                                        <dd><?= $this->Html->link(
                                            h($output->Address),
                                            [
                                                'controller'=>'Explorer',
                                                'action'=>'address',
                                                'lang'=>$lang,
                                                $output->Address,
                                            ]
                                        ) ?></dd>
                                    </dl>
                                    <hr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                <?= __('No Outputs') ?>
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
