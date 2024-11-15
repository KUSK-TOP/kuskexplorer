<?php
declare(strict_types=1);

namespace App\Test\TestCase\Middleware;

use App\Middleware\MaintenanceMiddleware;
use Cake\TestSuite\TestCase;

/**
 * App\Middleware\MaintenanceMiddleware Test Case
 */
class MaintenanceMiddlewareTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Middleware\MaintenanceMiddleware
     */
    protected $Maintenance;

    /**
     * Test process method
     *
     * @return void
     * @uses \App\Middleware\MaintenanceMiddleware::process()
     */
    public function testProcess(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
