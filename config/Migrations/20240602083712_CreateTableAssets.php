<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateTableAssets extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     * @return void
     */
    public function change(): void
    {
        $table = $this->table('assets');
        $table
            ->addColumn('asset', 'string', [ 
                'default' => null,
                'limit' => 65,
                'null' => false,
            ])
            ->addIndex('asset', ['unique'=>true])
            ->addColumn('block_height', 'biginteger', [
                'default' => null,
                'null' => false,
            ])
            ->addColumn('transaction_hash', 'string', [
                'default' => null,
                'limit' => 65,
                'null' => false,
            ])
            ->addColumn('asset_alias', 'string', [
                'default' => null,
                'limit' => 100,
                'null' => true,
            ])
            ->addColumn('asset_definition_decimals', 'integer', [
                'default' => null,
                'null' => false,
            ])
            ->addColumn('asset_definition_description', 'string', [
                'default' => null,
                'limit' => 100,
                'null' => true,
            ])
            ->addColumn('asset_definition_name', 'string', [
                'default' => null,
                'limit' => 100,
                'null' => false,
            ])
            ->addColumn('asset_definition_symbol', 'string', [
                'default' => null,
                'limit' => 100,
                'null' => false,
            ])
            ->addColumn('status', 'boolean', [
                'default' => false,
                'null' => false,
            ])
            ->addColumn('created', 'datetime', [
                'default' => 'CURRENT_TIMESTAMP',
                'null' => false,
            ])
            ->addColumn('modified', 'datetime', [
                'default' => 'CURRENT_TIMESTAMP',
                'update' => 'CURRENT_TIMESTAMP()',
                'null' => false,
            ])
            ->create();
    }
}
