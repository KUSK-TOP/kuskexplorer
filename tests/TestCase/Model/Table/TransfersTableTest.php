<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\TransfersTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\TransfersTable Test Case
 */
class TransfersTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\TransfersTable
     */
    protected $Transfers;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.Transfers',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Transfers') ? [] : ['className' => TransfersTable::class];
        $this->Transfers = $this->getTableLocator()->get('Transfers', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Transfers);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\TransfersTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test createTransfer method
     *
     * @return void
     * @uses \App\Model\Table\TransfersTable::createTransfer()
     */
    public function testCreateTransfer(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test getTransfers method
     *
     * @return void
     * @uses \App\Model\Table\TransfersTable::getTransfers()
     */
    public function testGetTransfers(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
