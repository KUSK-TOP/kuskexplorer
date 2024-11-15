<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateTableAddresses extends AbstractMigration
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
        $addresses = $this->table('addresses');
        $addresses
            ->addColumn('address', 'string', [
                'default' => null,
                'limit' => 50,
                'null' => false
            ])
            ->addColumn('balance', 'biginteger', [
                'default' => 0,
                'null' => false
            ])
            ->addColumn('asset', 'string', [
                'default' => null,
                'limit' => 65,
                'null' => false
            ])
            ->addIndex(['address','asset'], ['unique' => true])
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
