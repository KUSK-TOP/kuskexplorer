<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AddressesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AddressesTable Test Case
 */
class AddressesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\AddressesTable
     */
    protected $Addresses;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.Addresses',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Addresses') ? [] : ['className' => AddressesTable::class];
        $this->Addresses = $this->getTableLocator()->get('Addresses', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Addresses);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\AddressesTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test credit method
     *
     * @return void
     * @uses \App\Model\Table\AddressesTable::credit()
     */
    public function testCredit(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test debit method
     *
     * @return void
     * @uses \App\Model\Table\AddressesTable::debit()
     */
    public function testDebit(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test getAddress method
     *
     * @return void
     * @uses \App\Model\Table\AddressesTable::getAddress()
     */
    public function testGetAddress(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test top method
     *
     * @return void
     * @uses \App\Model\Table\AddressesTable::top()
     */
    public function testTop(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
