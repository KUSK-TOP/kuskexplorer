<?php
declare(strict_types=1);

use Migrations\AbstractSeed;

/**
 * Configs seed.
 */
class DataSeed extends AbstractSeed
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
                "key"=>"last_block",
                "value"=>"0",
            ],
            [
                "key"=>"mined_coins",
                "value"=>"0",
            ],
        ];

        $table = $this->table('data');
        $table->insert($data)->save();
    }
}
