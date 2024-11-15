<?php
/**
 * @var \App\View\AppView $this
 */
    $name = 'Site down for maintenance';
    $this->assign('title', __('Site down for maintenance') .' - ');
    $this->layout = 'error';
?>
        <main>
            <div class="container px-4 py-4">
                <div class="row">
                    <div class="col">
                        <div class="card text-bg-light mb-2">
                            <div class="card-header">
                                <span class="fw-bold"><i class="bi bi-cone-striped mx-1"></i><?= __('Maintenance') ?></span>
                            </div>
                            <div class="card-body ">
                                <p>Site is now in maintenance mode.</p>
                                <p>We apologize for any inconvenience.</p>
                                <p>Improvements have been made across many areas.</p>
                                <p>This maintenance will consist of:
                                    <ol>
                                        <li>Gathering information about the assets/tokens.</li>
                                        <li>Linking balances with the different assets/tokens.</li>
                                        <li>⏳ Running the script that will parse all the blocks to extract all the necessary asset/token and address information.( This task can take up to an hour or more )</li>
                                    </ol>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>