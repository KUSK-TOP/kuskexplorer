<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * TransfersFixture
 */
class TransfersFixture extends TestFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'block_number' => 1,
                'transaction_hash' => 'Lorem ipsum dolor sit amet',
                'from' => 'Lorem ipsum dolor sit amet',
                'to' => 'Lorem ipsum dolor sit amet',
                'amount' => 1,
                'source_asset' => 'Lorem ipsum dolor sit amet',
                'destination_asset' => 'Lorem ipsum dolor sit amet',
                'created' => '2024-06-02 12:24:58',
                'modified' => '2024-06-02 12:24:58',
            ],
        ];
        parent::init();
    }
}
