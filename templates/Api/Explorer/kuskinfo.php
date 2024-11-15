<?php
/**
 * @var \App\View\AppView $this
 */
    $this->assign('title', __('KUSK Information').' - ');
?>
        <main>
            <div class="container px-4 py-5">
                <h2 class="pb-2 border-bottom"><?= __('KUSK Information') ?></h2>
                <?php if (isset($kuskinfo)): ?>
                <p>Please set the header <code>Accept</code> to <code>application/json</code> or append <code>.json</code> to your URL in order to get <code>JSON</code> back.</p>
                <dl>
                    <?php foreach ($kuskinfo as $key => $value): ?>
                    <dt><?= h($key)?></dt>
                    <dd><code><?= h($value) ?></code></dd>
                    <?php endforeach; ?>
                </dl>
                <?php else: ?>
                <p><?= __('While using {0} an error occurred.', [$api_host]) ?></p>
                <?php endif; ?>
            </div>
        </main>
