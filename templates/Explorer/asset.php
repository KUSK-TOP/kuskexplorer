<?php
/**
 * @var \App\View\AppView $this
 */
    $name = 'Asset';
    $this->assign('title', __('Asset').' - ');
    $lang=$this->request->getParam('lang');
?>
        <main>
            <div class="container px-4 py-1">
                <?php if ($asset): ?>
                <div class="row">
                    <div class="col">
                        <div class="card text-bg-light mb-2">
                            <div class="card-header fw-bold"><i class="bi bi-currency-exchange mx-1"></i><?= __('Asset') ?></div>
                            <div class="card-body">
                                <dl>
                                    <?php if (isset($asset->Status)): ?>
                                    <dt><?= __('Status') ?></dt>
                                    <dd class="text-break"><?= $asset->Status?'<i class="bi bi-check-circle text-success mx-1"></i>':'<i class="bi bi-x-circle text-danger mx-1"></i>' ?></dd>
                                    <?php endif; ?>
                                    <?php if (!empty($asset->Type)): ?>
                                    <dt><?= __('Type') ?></dt>
                                    <dd class="text-break"><?= h($asset->Type) ?></dd>
                                    <?php endif; ?>
                                    <dt><?= __('Quorum') ?></dt>
                                    <dd class="text-break"><?= h($asset->Quorum) ?></dd>
                                    <dt><?= __('Key Index') ?></dt>
                                    <dd class="text-break"><?= h($asset->KeyIndex) ?></dd>
                                    <dt><?= __('Derive Rule') ?></dt>
                                    <dd class="text-break"><?= h($asset->DeriveRule) ?></dd>
                                    <dt><?= __('ID') ?></dt>
                                    <dd class="text-break"><?= h($asset->ID) ?></dd>
                                    <dt><?= __('Alias') ?></dt>
                                    <dd class="text-break"><?= h($asset->Alias) ?></dd>
                                    <dt><?= __('Vm Version') ?></dt>
                                    <dd class="text-break"><?= h($asset->VmVersion) ?></dd>
                                    <dt><?= __('Issue Program') ?></dt>
                                    <dd class="text-break"><?= h((empty($asset->IssueProgram))?'None':$asset->IssueProgram) ?></dd>
                                    <?php if (!empty($asset->RawDefinitionByte)): ?>
                                    <dt><?= __('Raw Definition Byte') ?></dt>
                                    <dd class="text-break"><?= h($asset->RawDefinitionByte) ?></dd>
                                    <?php endif; ?>
                                    <dt><?= __('Limit Height') ?></dt>
                                    <dd class="text-break"><?= h($asset->LimitHeight) ?></dd>
                                    <?php if (isset($asset->Definition->Decimals)): ?>
                                    <dt><?= __('Definition Decimals') ?></dt>
                                    <dd class="text-break"><?= h($asset->Definition->Decimals) ?></dd>
                                    <?php endif; ?>
                                    <?php if (isset($asset->Definition->Description)): ?>
                                    <dt><?= __('Definition Description') ?></dt>
                                    <dd class="text-break"><?= h($asset->Definition->Description) ?></dd>
                                    <?php endif; ?>
                                    <?php if (isset($asset->Definition->Name)): ?>
                                    <dt><?= __('Definition Name') ?></dt>
                                    <dd class="text-break"><?= h($asset->Definition->Name) ?></dd>
                                    <?php endif; ?>
                                    <?php if (isset($asset->Definition->Symbol)): ?>
                                    <dt><?= __('Definition Symbol') ?></dt>
                                    <dd class="text-break"><?= h($asset->Definition->Symbol) ?></dd>
                                    <?php endif; ?>
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
