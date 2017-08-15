<?php

use Eyewitness\Eye\App\Api\Api;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Client;

class ApiTest extends TestCase
{
    protected $api;

    protected $guzzle;

    protected $response;

    public function setUp()
    {
        parent::setUp();

        $this->guzzle = Mockery::mock(GuzzleHttp\Client::class);
        $this->app->instance(GuzzleHttp\Client::class, $this->guzzle);

        $this->api = new Api;
        $this->response = new Response(200, ['Content-Type' => 'application/json'], json_encode(['ok']));
    }

    public function test_does_not_send_if_api_disabled()
    {
        $this->app['config']->set('eyewitness.api_enabled', false);

        $this->guzzle->shouldReceive('post')->never();

        $this->api->up();
    }

    public function test_install()
    {
        $this->guzzle->shouldReceive('post')->once()->andReturn($this->response);
        $result = $this->api->install(['example' => 'test']);

        $this->assertEquals(['ok'], $result);
    }

    public function test_send_install_email()
    {
        $this->guzzle->shouldReceive('post')->once()->andReturn($this->response);
        $this->api->sendInstallEmail([]);
    }

    public function test_send_queue_ping()
    {
        $this->guzzle->shouldReceive('post')->once()->andReturn($this->response);
        $this->api->sendQueuePing('test', 0, []);
    }

    public function test_send_queue_failing_ping()
    {
        $this->guzzle->shouldReceive('post')->once()->andReturn($this->response);
        $this->api->sendQueueFailingPing('test', 'other', 'default', null, null);
    }

    public function test_send_scheduler_start_ping()
    {
        $this->guzzle->shouldReceive('post')->once()->andReturn($this->response);
        $this->api->sendSchedulerStartPing([]);
    }

    public function test_send_scheduler_finish_ping()
    {
        $this->guzzle->shouldReceive('post')->once()->andReturn($this->response);
        $this->api->sendSchedulerFinishPing([]);
    }

    public function test_send_webhook_ping()
    {
        $this->guzzle->shouldReceive('post')->once()->andReturn($this->response);
        $this->api->sendWebhookPing([]);
    }

    public function test_up()
    {
        $this->guzzle->shouldReceive('post')->once()->andReturn($this->response);
        $this->api->up();
    }

    public function test_down()
    {
        $this->guzzle->shouldReceive('post')->once()->andReturn($this->response);
        $this->api->down();
    }
}
