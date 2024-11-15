<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateTableTransfers extends AbstractMigration
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
        $transfers = $this->table('transfers');
        $transfers
            ->addColumn('block_number', 'biginteger', [
                'default' => null,
                'null' => false
            ])
            ->addColumn('transaction_hash', 'string', [
                'default' => null,
                'limit' => 65,
                'null' => false
            ])
            ->addColumn('from', 'string', [
                'default' => null,
                'limit' => 50,
                'null' => false
            ])
            ->addIndex('from')
            ->addColumn('to', 'string', [
                'default' => null,
                'limit' => 50,
                'null' => false
            ])
            ->addIndex('to')
            ->addColumn('amount', 'biginteger', [
                'default' => null,
                'null' => false
            ])
            ->addColumn('source_asset', 'string', [
                'default' => null,
                'limit' => 65,
                'null' => false
            ])
            ->addColumn('destination_asset', 'string', [
                'default' => null,
                'limit' => 65,
                'null' => false
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
