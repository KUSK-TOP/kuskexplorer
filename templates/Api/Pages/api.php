<?php
/**
 * @var \App\View\AppView $this
 */
    $this->assign('title', __('API').' - ');
    $domain = $this->request->getEnv('HTTP_HOST');
    $https = ($domain == 'explorer.kusk.top') ? 'on' : 'off';
    $protocol = ($https == 'on') ? 'https' : 'http';
?>
        <main>
            <div class="container px-4 py-5">
                <h2 class="pb-2 border-bottom"><?= __('API') ?></h2>
                <div class="row">
                    <div class="col col-3">
                        <h4>Table of Contents</h4>
                        <ul>
                            <li><a href="#introduction">Introduction</a></li>
                            <li><a href="#netstats">Net Stats</a></li>
                            <li><a href="#kuskinformation">KUSK Information</a></li>
                        </ul>
                    </div>
                    <div class="col">

                        <h3><a name="introduction">Introduction</a></h3>
                        <p>It's very important that you set the the header Accept to <code>application/json</code> or append <code>.json</code> to your URL.</p>
                        <p>Otherwise the system will return an HTML response.</p>
                        <div class="container py-2">
                            <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
                              <symbol id="exclamation-triangle-fill" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                              </symbol>
                            </svg>
                            <div class="alert alert-danger d-flex align-items-center" role="alert">
                                <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                                <div>This API is still in Alpha and will change on a regular basis.<br/>Please refer to this page on a regular basis.</div>
                            </div>
                        </div>

                        <h3><a name="netstats">Net Stats</a></h3>
                        <p>Returns the Main Net status.</p>
                        <p>Example with extension:</p>
                        <p><?php
                            $url = $protocol.'://'.$domain.$this->Url->build(
                                [
                                    'controller'=>'Explorer',
                                    'action'=>'netstats',
                                    'prefix'=>'Api'
                                ]
                            );
                            echo "<a class=\"text-break\" href=\"{$url}.json\" target=\"_blank\">{$url}.json</a>";
                        ?></p>
                        <p>Example using cURL:</p>
                        <pre><code class="language-shell">$ curl \
-H 'Accept: application/json' \
"<?= $url ?>"</code></pre>
                        <p>Returns:</p>
                        <pre><code class="language-json">{
    "code": 200,
    "message": "OK",
    "netstats": {
        "network": "mainnet",
        "version": "1.0.3",
        "height": 38704,
        "supply": 210000000000000000,
        "mined_coins": 24866691612909558,
        "hash_rate": 79646304,
        "difficulty": 1353987169
    }
}</code></pre>

                        <h3><a name="kuskinformation">KUSK Information</a></h3>
                        <p>Returns KUSK information.</p>
                        <p>Example with extension:</p>
                        <p><?php
                            $url = $protocol.'://'.$domain.$this->Url->build(
                                [
                                    'controller'=>'Explorer',
                                    'action'=>'kuskinfo',
                                    'prefix'=>'Api'
                                ]
                            );
                            echo "<a class=\"text-break\" href=\"{$url}.json\" target=\"_blank\">{$url}.json</a>";
                        ?></p>
                        <p>Example using cURL:</p>
                        <pre><code class="language-shell">$ curl \
-H 'Accept: application/json' \
"<?= $url ?>"</code></pre>
                        <p>Returns:</p>
                        <pre><code class="language-json">{
    "code": 200,
    "message": "OK",
    "kuskinfo": {
        "name": "KUSK",
        "symbol": "KUSK",
        "supply": "2.1 Billion KUSK",
        "block_interval": "3 minutes",
        "block_reward": "1,000 KUSK (For every 175,200 blocks, the reward is reduced by 10%)",
        "genesis_block_reward": "210 Million KUSK",
        "algorithm": "Tensority",
        "webpage": "https:\/\/kusk.top",
        "explorer": "https:\/\/explorer.kusk.top",
        "github": "https:\/\/github.com\/KUSK-Project",
        "twitter": "https:\/\/twitter.com\/kusk_org",
        "telegram": "https:\/\/t.me\/kusk",
        "bitcointalk": "https:\/\/bitcointalk.org\/index.php?topic=5495712.0",
        "miningpoolstats": "https:\/\/miningpoolstats.stream\/kusk",
        "coinpaprika": "https:\/\/coinpaprika.com\/coin\/ey-kusk\/",
        "livecoinwatch": "https:\/\/www.livecoinwatch.com\/price\/KUSK-KUSK",
        "blockspotio": "https:\/\/blockspot.io\/coin\/kusk\/"
    }
}</code></pre>

                    </div>
                </div>
            </div>
        </main>
