<?php
declare(strict_types=1);

use Migrations\AbstractSeed;

/**
 * Assets seed.
 */
class AssetsSeed extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeds is available here:
     * https://book.cakephp.org/phinx/0/en/seeding.html
     *
     * @return void
     */
    public function run(): void
    {
        $data = [
            [
                'asset' => 'ffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffff',
                'block_height' => 0,
                'transaction_hash' => '0000000000000000000000000000000000000000000000000000000000000000',
                'asset_alias' => 'KUSK',
                'asset_definition_decimals' => 8,
                'asset_definition_description' => 'Kusk Official Issue',
                'asset_definition_name' => 'KUSK',
                'asset_definition_symbol' => 'KUSK',
                'status' => true,
            ]
        ];

        $table = $this->table('assets');
        $table->insert($data)->save();
    }
}
