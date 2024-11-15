<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * AssetsFixture
 */
class AssetsFixture extends TestFixture
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
                'asset' => 'Lorem ipsum dolor sit amet',
                'asset_alias' => 'Lorem ipsum dolor sit amet',
                'asset_definition_decimals' => 1,
                'asset_definition_description' => 'Lorem ipsum dolor sit amet',
                'asset_definition_name' => 'Lorem ipsum dolor sit amet',
                'asset_definition_symbol' => 'Lorem ipsum dolor sit amet',
                'created' => '2024-06-02 12:25:09',
                'modified' => '2024-06-02 12:25:09',
            ],
        ];
        parent::init();
    }
}
