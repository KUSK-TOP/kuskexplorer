<?php
/**
 * @var \App\View\AppView $this
 */
use Cake\I18n\Number;

    $name = 'Home';
    $this->assign('title', __('Home').' - ');
    $lang=$this->request->getParam('lang');
?>
        <main>
            <div class="container px-4 py-1">
                <?php if(isset($currentBlock)): ?>
                <div class="row">
                    <div class="col">
                        <div class="card text-bg-light mb-2">
                            <div class="card-header">
                                <span class="fw-bold"><i class="bi bi-box mx-1"></i><?= __('Last Block') ?></span>
                            </div>
                            <div class="card-body "><?= h(Number::format($currentBlock)) ?></div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card text-bg-light mb-2">
                            <div class="card-header fw-bold"><i class="bi bi-graph-up-arrow mx-1"></i><?= __('Hash Rate') ?></div>
                            <div class="card-body"><?= h($hashRate) ?></div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card text-bg-light mb-2">
                            <div class="card-header fw-bold"><i class="bi bi-graph-up mx-1"></i><?= __('Difficulty') ?></div>
                            <div class="card-body"><?= h($difficulty) ?></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="card text-bg-light mb-2">
                            <div class="card-header fw-bold"><i class="bi bi-activity mx-1"></i><?= __('Pending Transactions') ?></div>
                            <div class="card-body"><?= ($pendingCount > 0)?$this->Html->link(
                                __n(
                                    '{0} Pending Transaction',
                                    '{0} Pending Transactions',
                                    $pendingCount,
                                    $pendingCount
                                ),
                                [
                                    'controller'=>'Explorer',
                                    'action'=>'pendingtransactions',
                                    'lang'=>$lang
                                ]
                            ):__('None') ?></div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card text-bg-light mb-2">
                            <div class="card-header fw-bold"><i class="bi bi-coin mx-1"></i><?= __('Coins Mined') ?></div>
                            <div class="card-body"><?= h($mined) ?> KUSK</div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card text-bg-light mb-2">
                            <div class="card-header fw-bold"><i class="bi bi-cash-coin mx-1"></i><?= __('Supply') ?></div>
                            <div class="card-body"><?= h($supply) ?> KUSK</div>
                        </div>
                    </div>
                </div>
                <?php if (isset($blocks) && count($blocks)>0): ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th><?= __('Number') ?></th>
                                <th><?= __('Hash') ?></th>
                                <th><?= __('Time Stamp') ?></th>
                                <th><?= __('Nonce') ?></th>
                                <th><?= __('Miner') ?></th>
                                <th><?= __('Transactions') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($blocks as $index => $block): ?>
                            <tr>
                                <td><?= $this->Html->link(
                                    Number::format($block->Height),
                                    [
                                        'controller'=>'Explorer',
                                        'action'=>'block',
                                        'lang'=>$lang,
                                        $block->Height
                                    ]
                                ) ?></td>
                                <td><?= $this->Html->link(
                                    sprintf("%s...%s", substr($block->Hash, 0 ,10), substr($block->Hash, -10)),
                                    [
                                        'controller'=>'Explorer',
                                        'action'=>'block',
                                        'lang'=>$lang,
                                        $block->Height
                                    ]
                                ) ?></td>
                                <td><span id="block<?= $index ?>"><?= h(__('{0} UTC', $block->TimestampFormatted)) ?></span></td>
                                <td><?= h(Number::format($block->Nonce)) ?></td>
                                <td><?php 
                                    switch ($miners[$index]) {
                                        case 'ey1q2utpp6mqkh3z4edwqflg7ahhz9x0rqadt2cy4c':
                                            echo '<a href="https://kusk.maxfeehunter.com" target="_blank">maxfeehunter.com</a>';
                                            break;
                                        case 'ey1qgypd89j8hs38q7treqej47euzw8ss7dntvkl7v':
                                            echo '<a href="http://fcmx.net/ey-pool" target="_blank">fcmx.net</a>';
                                            break;
                                        default:
                                            echo $this->Html->link(
                                                sprintf("%s...%s", substr($miners[$index], 0 ,10), substr($miners[$index], -10)),
                                                ['action'=>'address', $miners[$index]],
                                            );
                                    }
                                ?></td>
                                <td><?= $this->Html->link(
                                    __(
                                        '{0} Tx',
                                        Number::format(count($block->Transactions))
                                    ),
                                    [
                                        'controller'=>'Explorer',
                                        'action'=>'blocktransactions',
                                        'lang'=>$lang,
                                        $block->Height
                                    ]
                                ) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="paginator">
                        <ul class="pagination">
                            <?php if (isset($previous)): ?>
                            <li><a class="btn btn-primary" href="<?= $this->Url->build('/'.$lang.'/from') . "/{$previous}" ?>">&lt; <?= __('previous') ?></a></li>
                            <?php else: ?>
                            <li><a class="btn btn-danger disabled" role="button" aria-disabled="true" href="" onclick="return false;">&lt; <?= __('previous') ?></a></li>
                            <?php endif; ?>

                            <?php if(isset($next)): ?>
                            <li><a class="btn btn-primary" href="<?= $this->Url->build('/'.$lang.'/from') . "/{$next}" ?>"><?= __('next') ?> &gt;</a></li>
                            <?php else: ?>
                            <li><a class="btn btn-danger disabled" role="button" aria-disabled="true" href="" onclick="return false;"><?= __('next') ?> &gt;</a></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
                <?php else: ?>
                <p><?= __('No blocks') ?></p>
                <?php endif; ?>
            </div>
                <?php else: ?>
                <p><?= __('While using {0} an error occurred.', [$api_host]) ?></p>
                <?php endif; ?>
        </main>
<script>
    document.addEventListener("DOMContentLoaded", function(){
        for (index = 0; index < 10; index++) {
            const elem = document.getElementById('block' + index);
            const datetime = new Date(elem.innerText);
            elem.innerText = datetime.toLocaleString();
        }
    });
</script>