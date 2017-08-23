<?php

use Eyewitness\Eye\App\Witness\Scheduler;
use Eyewitness\Eye\App\Witness\Database;
use Eyewitness\Eye\App\Witness\Request;
use Eyewitness\Eye\App\Witness\Server;
use Eyewitness\Eye\App\Witness\Queue;
use Eyewitness\Eye\App\Witness\Email;
use Eyewitness\Eye\App\Witness\Disk;
use Eyewitness\Eye\App\Witness\Log;
use Eyewitness\Eye\Eye;

class ServerControllerTest extends TestCase
{
    protected $scheduler;

    protected $database;

    protected $request;

    protected $server;

    protected $queue;

    protected $email;

    protected $disk;

    protected $log;

    public function setUp()
    {
        parent::setUp();

        $this->scheduler = Mockery::mock(Scheduler::class);
        $this->app->instance(Scheduler::class, $this->scheduler);

        $this->database = Mockery::mock(Database::class);
        $this->app->instance(Database::class, $this->database);

        $this->request = Mockery::mock(Request::class);
        $this->app->instance(Request::class, $this->request);

        $this->server = Mockery::mock(Server::class);
        $this->app->instance(Server::class, $this->server);

        $this->queue = Mockery::mock(Queue::class);
        $this->app->instance(Queue::class, $this->queue);

        $this->email = Mockery::mock(Email::class);
        $this->app->instance(Email::class, $this->email);

        $this->disk = Mockery::mock(Disk::class);
        $this->app->instance(Disk::class, $this->disk);

        $this->log = Mockery::mock(Log::class);
        $this->app->instance(Log::class, $this->log);

        $this->app['config']->set('eyewitness.monitor_scheduler', false);
        $this->app['config']->set('eyewitness.monitor_database', false);
        $this->app['config']->set('eyewitness.monitor_request', false);
        $this->app['config']->set('eyewitness.monitor_disk', false);
        $this->app['config']->set('eyewitness.monitor_email', false);
        $this->app['config']->set('eyewitness.monitor_queue', false);
        $this->app['config']->set('eyewitness.monitor_log', false);
    }

    public function test_ping_server_honors_config()
    {
        $this->server->shouldReceive('check')->once()->andReturn(['php' => 'example']);

        $this->scheduler->shouldReceive('check')->never();
        $this->queue->shouldReceive('check')->never();
        $this->database->shouldReceive('check')->never();
        $this->request->shouldReceive('check')->never();
        $this->email->shouldReceive('check')->never();
        $this->disk->shouldReceive('check')->never();
        $this->log->shouldReceive('check')->never();

        $response = $this->call('GET', $this->api.'server'.$this->auth);

        $this->assertEquals(json_encode(['server_stats' => ['php' => 'example'], 'eyewitness_version' => Eye::EYE_VERSION, 'application_environment' => 'testing', 'application_debug' => '1', 'eyewitness_config' => config('eyewitness')]), $response->getContent());
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_scheduler()
    {
        $this->app['config']->set('eyewitness.monitor_scheduler', true);

        $this->server->shouldReceive('check')->once()->andReturn(['php' => 'example']);
        $this->scheduler->shouldReceive('check')->once()->andReturn(['example' => 'list']);

        $this->queue->shouldReceive('check')->never();
        $this->database->shouldReceive('check')->never();
        $this->request->shouldReceive('check')->never();
        $this->email->shouldReceive('check')->never();
        $this->disk->shouldReceive('check')->never();
        $this->log->shouldReceive('check')->never();

        $response = $this->call('GET', $this->api.'server'.$this->auth);

        $this->assertEquals(json_encode(['server_stats' => ['php' => 'example'], 'eyewitness_version' => Eye::EYE_VERSION, 'application_environment' => 'testing', 'application_debug' => '1', 'eyewitness_config' => config('eyewitness'), 'scheduler' => ['example' => 'list']]), $response->getContent());
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_email()
    {
        $this->app['config']->set('eyewitness.monitor_email', true);

        $this->server->shouldReceive('check')->once()->andReturn(['php' => 'example']);
        $this->email->shouldReceive('check')->once()->andReturn(['email' => 'testing']);
        $this->email->shouldReceive('send')->once();

        $this->scheduler->shouldReceive('check')->never();
        $this->queue->shouldReceive('check')->never();
        $this->database->shouldReceive('check')->never();
        $this->request->shouldReceive('check')->never();
        $this->disk->shouldReceive('check')->never();
        $this->log->shouldReceive('check')->never();

        $response = $this->call('GET', $this->api.'server'.$this->auth);

        $this->assertEquals(json_encode(['server_stats' => ['php' => 'example'], 'eyewitness_version' => Eye::EYE_VERSION, 'application_environment' => 'testing', 'application_debug' => '1', 'eyewitness_config' => config('eyewitness'), 'email_stats' => ['email' => 'testing']]), $response->getContent());
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_queue()
    {
        $this->app['config']->set('eyewitness.monitor_queue', true);

        $this->server->shouldReceive('check')->once()->andReturn(['php' => 'example']);
        $this->queue->shouldReceive('check')->once()->andReturn(['list']);
        $this->queue->shouldReceive('deploySonar')->once();

        $this->scheduler->shouldReceive('check')->never();
        $this->database->shouldReceive('check')->never();
        $this->request->shouldReceive('check')->never();
        $this->email->shouldReceive('check')->never();
        $this->disk->shouldReceive('check')->never();
        $this->log->shouldReceive('check')->never();

        $response = $this->call('GET', $this->api.'server'.$this->auth);

        $this->assertEquals(json_encode(['server_stats' => ['php' => 'example'], 'eyewitness_version' => Eye::EYE_VERSION, 'application_environment' => 'testing', 'application_debug' => '1', 'eyewitness_config' => config('eyewitness'), 'queue_stats' => ['list']]), $response->getContent());
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_database()
    {
        $this->app['config']->set('eyewitness.monitor_database', true);

        $this->server->shouldReceive('check')->once()->andReturn(['php' => 'example']);
        $this->database->shouldReceive('check')->once()->andReturn(['db_status' => true]);

        $this->scheduler->shouldReceive('check')->never();
        $this->queue->shouldReceive('check')->never();
        $this->request->shouldReceive('check')->never();
        $this->email->shouldReceive('check')->never();
        $this->disk->shouldReceive('check')->never();
        $this->log->shouldReceive('check')->never();

        $response = $this->call('GET', $this->api.'server'.$this->auth);

        $this->assertEquals(json_encode(['server_stats' => ['php' => 'example'], 'eyewitness_version' => Eye::EYE_VERSION, 'application_environment' => 'testing', 'application_debug' => '1', 'eyewitness_config' => config('eyewitness'), 'db_stats' => ['db_status' => true]]), $response->getContent());
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_request()
    {
        $this->app['config']->set('eyewitness.monitor_request', true);

        $this->server->shouldReceive('check')->once()->andReturn(['php' => 'example']);
        $this->request->shouldReceive('check')->once()->andReturn(['count' => 5]);

        $this->scheduler->shouldReceive('check')->never();
        $this->queue->shouldReceive('check')->never();
        $this->database->shouldReceive('check')->never();
        $this->email->shouldReceive('check')->never();
        $this->disk->shouldReceive('check')->never();
        $this->log->shouldReceive('check')->never();

        $response = $this->call('GET', $this->api.'server'.$this->auth);

        $this->assertEquals(json_encode(['server_stats' => ['php' => 'example'], 'eyewitness_version' => Eye::EYE_VERSION, 'application_environment' => 'testing', 'application_debug' => '1', 'eyewitness_config' => config('eyewitness'), 'request_stats' => ['count' => 5]]), $response->getContent());
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_disk()
    {
        $this->app['config']->set('eyewitness.monitor_disk', true);

        $this->server->shouldReceive('check')->once()->andReturn(['php' => 'example']);
        $this->disk->shouldReceive('check')->once()->andReturn(['size' => 7]);

        $this->scheduler->shouldReceive('check')->never();
        $this->queue->shouldReceive('check')->never();
        $this->database->shouldReceive('check')->never();
        $this->request->shouldReceive('check')->never();
        $this->email->shouldReceive('check')->never();
        $this->log->shouldReceive('check')->never();

        $response = $this->call('GET', $this->api.'server'.$this->auth);

        $this->assertEquals(json_encode(['server_stats' => ['php' => 'example'], 'eyewitness_version' => Eye::EYE_VERSION, 'application_environment' => 'testing', 'application_debug' => '1', 'eyewitness_config' => config('eyewitness'), 'disk_stats' => ['size' => 7]]), $response->getContent());
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_log()
    {
        $this->app['config']->set('eyewitness.monitor_log', true);

        $this->server->shouldReceive('check')->once()->andReturn(['php' => 'example']);
        $this->log->shouldReceive('check')->once()->andReturn(['tests' => 'ok']);

        $this->scheduler->shouldReceive('check')->never();
        $this->queue->shouldReceive('check')->never();
        $this->database->shouldReceive('check')->never();
        $this->request->shouldReceive('check')->never();
        $this->email->shouldReceive('check')->never();
        $this->disk->shouldReceive('check')->never();

        $response = $this->call('GET', $this->api.'server'.$this->auth);

        $this->assertEquals(json_encode(['server_stats' => ['php' => 'example'], 'eyewitness_version' => Eye::EYE_VERSION, 'application_environment' => 'testing', 'application_debug' => '1', 'eyewitness_config' => config('eyewitness'), 'log_stats' => ['tests' => 'ok']]), $response->getContent());
        $this->assertEquals(200, $response->getStatusCode());
    }
}
