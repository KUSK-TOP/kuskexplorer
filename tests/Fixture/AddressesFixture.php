<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * AddressesFixture
 */
class AddressesFixture extends TestFixture
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
                'address' => 'Lorem ipsum dolor sit amet',
                'inbound' => 1,
                'outbound' => 1,
                'balance' => 1,
                'asset' => 'Lorem ipsum dolor sit amet',
                'created' => '2024-06-02 12:57:29',
                'modified' => '2024-06-02 12:57:29',
            ],
        ];
        parent::init();
    }
}
