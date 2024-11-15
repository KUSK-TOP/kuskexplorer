<?php
declare(strict_types=1);

namespace App\Controller\Api;

use KUSK\API;

use App\Controller\AppController;
use Cake\Core\Configure;

/**
 * API Explorer Controller
 * 
 * @method \App\Model\Entity\Explorer[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */

 class ExplorerController extends AppController
{
private API $api;

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code.
     *
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();

        $nodes = Configure::read('RPC');
        //$node_index = mt_rand(0,count($nodes)-1);

        $this->api = new API(
            $nodes[0]['host'], 
            $nodes[0]['access-token']
        );
    }

    /**
     * Net Stats
     * 
     * @return \Cake\Http\Response|null|void Renders view
     */

     public function netstats()
    {
        $nodes = Configure::read('RPC');
        $api_host = $nodes[0]['host'];
        $code = 200;
        $message = "OK";
        $netInfo = $this->api->getNetInfo();
        $currentBlock = $this->api->getBlock($netInfo->CurrentBlock);
        $hashRate = $this->api->getHashRate($netInfo->CurrentBlock);
        $mined = $this->fetchTable('Data')->getMined();

        $netstats = [
            "network" => $netInfo->NetworkID,
            "version" => $netInfo->VersionInfo->Version,
            "height" => $netInfo->CurrentBlock,
            "supply" => 210000000000000000,
            "mined_coins" => $mined,
            "hash_rate" => $hashRate->HashRate,
            "difficulty" => intval($currentBlock->Difficulty),
        ];

        $this->set(compact('api_host', 'code', 'message', 'netstats'));
        $this->viewBuilder()->setOption('serialize', ['code', 'message', 'netstats']);
    }

    /**
     * KUSK Information
     * 
     * @return \Cake\Http\Response|null|void Renders view
     */

     public function kuskinfo()
    {
        $nodes = Configure::read('RPC');
        $api_host = $nodes[0]['host'];
        $code = 200;
        $message = "OK";
        $netInfo = $this->api->getNetInfo();
        $currentBlock = $this->api->getBlock($netInfo->CurrentBlock);
        $hashRate = $this->api->getHashRate($netInfo->CurrentBlock);
        $mined = $this->fetchTable('Data')->getMined();

        $kuskinfo = [
            "name" => "KUSK",
            "symbol" => "KUSK",
            "supply" => "2.1 Billion KUSK",
            "block_interval" => "3 minutes",
            "block_reward" => "1,000 KUSK (For every 175,200 blocks, the reward is reduced by 10%)",
            "genesis_block_reward" => "210 Million KUSK",
            "algorithm" => "Tensority",
            "whitepaper" => "https://kusk.top/info/KUSKen.pdf",
            "webpage" => "https://kusk.top",
            "explorer" => "https://explorer.kusk.top",
            "github" => "https://github.com/KUSK-Project",
            "twitter" => "https://twitter.com/kusk_org",
            "telegram" => "https://t.me/kusk",
            "bitcointalk" => "https://bitcointalk.org/index.php?topic=5495712.0",
            "miningpoolstats" => "https://miningpoolstats.stream/kusk",
            "coinpaprika" => "https://coinpaprika.com/coin/ey-kusk/",
            "livecoinwatch" => "https://www.livecoinwatch.com/price/KUSK-KUSK",
            "blockspotio" => "https://blockspot.io/coin/kusk/",
        ];
        /*
        Project name: KUSK
        Coin symbol: KUSK
        Total Coins: 2.1 billion KUSK
        Block interval: 3 minutes
        Block Reward: 1000 KUSK
        For every 175,200 blocks, the reward is reduced by 10%.
        Genesis Block Rewards: 210 million KUSK
        Algorithms: Tensority
        Website: https://kusk.top/
        Explorer: https://explorer.kusk.top/
        GitHub: https://github.com/KUSK-Project
        Twitter: https://twitter.com/kusk_org
        Telegram: https://t.me/kusk
        Bitcointalk: https://bitcointalk.org/index.php?topic=5495712.0
        Miningpoolstats: https://miningpoolstats.stream/kusk
        Coinpaprika: https://coinpaprika.com/coin/ey-kusk/
        livecoinwatch: https://www.livecoinwatch.com/price/KUSK-KUSK
        Blockspot.io: https://blockspot.io/coin/kusk/ 
        */

        $this->set(compact('api_host', 'code', 'message', 'kuskinfo'));
        $this->viewBuilder()->setOption('serialize', ['code', 'message', 'kuskinfo']);
    }
}
