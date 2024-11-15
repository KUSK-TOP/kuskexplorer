<?php
/**
 * @var \App\View\AppView $this
 */
    $name = 'Assets';
    $this->assign('title', __('Assets').' - ');
    $lang=$this->request->getParam('lang');
?>
        <main>
            <div class="container px-4 py-1">
            <?php if (isset($assets)): ?>
                <div class="row">
                    <div class="col">
                        <div class="card text-bg-light mb-2">
                            <div class="card-header fw-bold"><i class="bi bi-currency-exchange mx-1"></i><?= __('Assets') ?></div>
                            <div class="card-body">
                                <?php if (count($assets)>0): ?>
                                <div class="table-responsive">
                                    <table class="table table-triped">
                                        <thead>
                                            <tr>
                                                <th><?= __('ID') ?></th>
                                                <th><?= __('Alias') ?></th>
                                                <th><?= __('Block') ?></th>
                                                <th><?= __('Transaction') ?></th>
                                                <th><?= __('Name') ?></th>
                                                <th><?= __('Symbol') ?></th>
                                                <th><?= __('Status') ?></th>
                                                <th><?= __('Description') ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($assets as $asset): ?>
                                            <tr>
                                                <td><?= $this->Html->link(
                                                    sprintf("%s...%s", substr($asset->asset, 0 ,10), substr($asset->asset, -10)),
                                                    ['controller'=>'Explorer', 'action'=>'asset', 'lang'=>$lang, $asset->asset]
                                                ) ?></td>
                                                <td><?= h($asset->asset_alias) ?></td>
                                                <td><?= $this->Html->link(
                                                    $asset->block_height,
                                                    ['controller'=>'Explorer', 'action'=>'block', 'lang'=>$lang, $asset->block_height]
                                                ) ?></td>
                                                <td><?php
                                                    if ($asset->block_height == 0) {
                                                        echo 'Genesis';
                                                    } else {
                                                        echo $this->Html->link(
                                                            sprintf("%s...%s", substr($asset->transaction_hash, 0 ,10), substr($asset->transaction_hash, -10)),
                                                            ['controller' => 'Explorer', 'action' => 'transaction', $asset->transaction_hash]
                                                        );                                                        
                                                    }
                                                 ?></td>
                                                <td><?= h($asset->asset_definition_name) ?></td>
                                                <td><?= h($asset->asset_definition_symbol) ?></td>
                                                <td><?= $asset->status?'<i class="bi bi-check-circle text-success mx-1"></i>':'<i class="bi bi-x-circle text-danger mx-1"></i>' ?></td>
                                                <td><?= h($asset->asset_definition_description) ?></td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <?php else: ?>
                                <p><?= __('No orders on this block') ?></p>
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
