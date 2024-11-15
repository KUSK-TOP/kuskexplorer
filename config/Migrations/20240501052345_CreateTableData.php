<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateTableData extends AbstractMigration
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
        $configs = $this->table('data');
        $configs
            ->addColumn('key', 'string', [
                'default' => null,
                'limit' => 100,
                'null' => false
            ])
            ->addIndex('key', ['unique'=>true])
            ->addColumn('value', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true
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
