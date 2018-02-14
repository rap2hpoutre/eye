<?php

namespace Eyewitness\Eye\Test\Controllers\DashboardTabs;

use Eyewitness\Eye\Test\TestCase;

class OverviewTabTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->artisan('migrate', ['--database' => 'testbench']);
    }

    public function test_overview_tab()
    {
        $response = $this->withSession(['eyewitness:auth' => 1])
                         ->get($this->home.'/dashboard');

        $response->assertStatus(200);
        $response->assertSee('PHP version');
        $response->assertSee('Laravel version');
        $response->assertSee(app()->version());
        $response->assertSee('Route cache');
    }

    public function test_debug_mode()
    {
        config(['app.debug' => true]);

        $response = $this->withSession(['eyewitness:auth' => 1])
                         ->get($this->home.'/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Enabled');
    }
}
