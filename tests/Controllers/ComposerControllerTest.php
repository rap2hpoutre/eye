<?php

use Eyewitness\Eye\App\Api\Api;

class ComposerControllerTest extends TestCase
{
    protected $eyeapi;

    public function setUp()
    {
        parent::setUp();

        $this->eyeapi = Mockery::mock(Api::class);
        $this->app->instance(Api::class, $this->eyeapi);
    }

    public function test_controller_required_authentication()
    {
        $response = $this->call('GET', $this->api.'composer');
        $this->assertEquals(json_encode(['error' => 'Unauthorized']), $response->getContent());
        $this->assertEquals(401, $response->getStatusCode());
    }

    public function test_controller_honours_config()
    {
        $this->app['config']->set('eyewitness.monitor_composer_lock', false);

        $this->eyeapi->shouldReceive('runComposerLockCheck')->never();

        $response = $this->call('GET', $this->api.'composer'.$this->auth);
        $this->assertEquals(json_encode(['error' => 'The composer route is disabled on the server']), $response->getContent());
        $this->assertEquals(405, $response->getStatusCode());
    }

    public function test_successful_composer_check()
    {
        $this->eyeapi->shouldReceive('runComposerLockCheck')->once()->andReturn(['example' => 'test']);

        $response = $this->call('GET', $this->api.'composer'.$this->auth);
        $this->assertEquals(json_encode(['example' => 'test']), $response->getContent());
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_failed_composer_check()
    {
        $this->eyeapi->shouldReceive('runComposerLockCheck')->once()->andReturn(null);

        $response = $this->call('GET', $this->api.'composer'.$this->auth);
        $this->assertEquals(json_encode(['error' => 'Could not run composer.lock check']), $response->getContent());
        $this->assertEquals(500, $response->getStatusCode());
    }
}
