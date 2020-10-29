<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ComissionsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ComissionsTable Test Case
 */
class ComissionsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ComissionsTable
     */
    protected $Comissions;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.Comissions',
        'app.Transactions',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Comissions') ? [] : ['className' => ComissionsTable::class];
        $this->Comissions = $this->getTableLocator()->get('Comissions', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Comissions);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
