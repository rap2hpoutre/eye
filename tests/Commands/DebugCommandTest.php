<?php

namespace Eyewitness\Eye\Test\Commands;

use Mockery;
use Eyewitness\Eye\Api;
use Eyewitness\Eye\Test\TestCase;
use Illuminate\Support\Facades\Artisan;

class DebugCommandTest extends TestCase
{
    protected $api;

    public function setUp()
    {
        parent::setUp();

        $this->api = Mockery::mock(Api::class);
        $this->app->instance(Api::class, $this->api);
    }

    public function test_command_displays_config()
    {
        $this->api->shouldReceive('sendTestPing')->once()->andReturn(['pass' => 'true',
                                                                      'code' => 200,
                                                                      'message' => 'okping']);

        $this->api->shouldReceive('sendTestAuthenticate')->once()->andReturn(['pass' => 'true',
                                                                              'code' => 200,
                                                                              'message' => 'okauth']);

        $this->artisan('eyewitness:debug');

        $output = Artisan::output();

        $this->assertTrue(str_contains($output, 'APP_TEST'));
        $this->assertTrue(str_contains($output, 'SECRET_TEST'));

        $this->assertTrue(str_contains($output, 'Status Code:'));
        $this->assertTrue(str_contains($output, '200'));
        $this->assertTrue(str_contains($output, 'Status Message'));
        $this->assertTrue(str_contains($output, 'okping'));
        $this->assertTrue(str_contains($output, 'okauth'));
    }
}
