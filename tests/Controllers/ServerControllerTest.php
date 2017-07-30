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

    public function testPingServerHonoursConfig()
    {
        $this->server->shouldReceive('check')->once()->andReturn(['php' => 'example']);

        $this->scheduler->shouldReceive('getScheduledEvents')->never();
        $this->queue->shouldReceive('pingAllTubes')->never();
        $this->database->shouldReceive('check')->never();
        $this->request->shouldReceive('check')->never();
        $this->email->shouldReceive('send')->never();
        $this->disk->shouldReceive('check')->never();
        $this->log->shouldReceive('check')->never();

        $response = $this->call('GET', $this->api.'server'.$this->auth);

        $this->assertEquals(json_encode(['server_stats' => ['php' => 'example'], 'eyewitness_version' => Eye::EYE_VERSION, 'application_environment' => 'testing', 'eyewitness_config' => config('eyewitness')]), $response->getContent());
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testScheduler()
    {
        $this->app['config']->set('eyewitness.monitor_scheduler', true);

        $this->server->shouldReceive('check')->once()->andReturn(['php' => 'example']);
        $this->scheduler->shouldReceive('getScheduledEvents')->once()->andReturn(['example' => 'list']);

        $this->queue->shouldReceive('pingAllTubes')->never();
        $this->database->shouldReceive('check')->never();
        $this->request->shouldReceive('check')->never();
        $this->email->shouldReceive('send')->never();
        $this->disk->shouldReceive('check')->never();
        $this->log->shouldReceive('check')->never();

        $response = $this->call('GET', $this->api.'server'.$this->auth);

        $this->assertEquals(json_encode(['server_stats' => ['php' => 'example'], 'eyewitness_version' => Eye::EYE_VERSION, 'application_environment' => 'testing', 'eyewitness_config' => config('eyewitness'), 'scheduler' => ['example' => 'list']]), $response->getContent());
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testEmail()
    {
        $this->app['config']->set('eyewitness.monitor_email', true);

        $this->server->shouldReceive('check')->once()->andReturn(['php' => 'example']);
        $this->email->shouldReceive('send')->once();

        $this->scheduler->shouldReceive('getScheduledEvents')->never();
        $this->queue->shouldReceive('pingAllTubes')->never();
        $this->database->shouldReceive('check')->never();
        $this->request->shouldReceive('check')->never();
        $this->disk->shouldReceive('check')->never();
        $this->log->shouldReceive('check')->never();

        $response = $this->call('GET', $this->api.'server'.$this->auth);

        $this->assertEquals(json_encode(['server_stats' => ['php' => 'example'], 'eyewitness_version' => Eye::EYE_VERSION, 'application_environment' => 'testing', 'eyewitness_config' => config('eyewitness')]), $response->getContent());
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testQueue()
    {
        $this->app['config']->set('eyewitness.monitor_queue', true);

        $this->server->shouldReceive('check')->once()->andReturn(['php' => 'example']);
        $this->queue->shouldReceive('allTubeStats')->once()->andReturn(['list']);

        $this->scheduler->shouldReceive('getScheduledEvents')->never();
        $this->database->shouldReceive('check')->never();
        $this->request->shouldReceive('check')->never();
        $this->email->shouldReceive('send')->never();
        $this->disk->shouldReceive('check')->never();
        $this->log->shouldReceive('check')->never();

        $response = $this->call('GET', $this->api.'server'.$this->auth);

        $this->assertEquals(json_encode(['server_stats' => ['php' => 'example'], 'eyewitness_version' => Eye::EYE_VERSION, 'application_environment' => 'testing', 'eyewitness_config' => config('eyewitness'), 'queue_stats' => ['list']]), $response->getContent());
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testDatabase()
    {
        $this->app['config']->set('eyewitness.monitor_database', true);

        $this->server->shouldReceive('check')->once()->andReturn(['php' => 'example']);
        $this->database->shouldReceive('check')->once()->andReturn(['db_status' => true]);

        $this->scheduler->shouldReceive('getScheduledEvents')->never();
        $this->queue->shouldReceive('pingAllTubes')->never();
        $this->request->shouldReceive('check')->never();
        $this->email->shouldReceive('send')->never();
        $this->disk->shouldReceive('check')->never();
        $this->log->shouldReceive('check')->never();

        $response = $this->call('GET', $this->api.'server'.$this->auth);

        $this->assertEquals(json_encode(['server_stats' => ['php' => 'example'], 'eyewitness_version' => Eye::EYE_VERSION, 'application_environment' => 'testing', 'eyewitness_config' => config('eyewitness'), 'db_stats' => ['db_status' => true]]), $response->getContent());
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testRequest()
    {
        $this->app['config']->set('eyewitness.monitor_request', true);

        $this->server->shouldReceive('check')->once()->andReturn(['php' => 'example']);
        $this->request->shouldReceive('check')->once()->andReturn(['count' => 5]);

        $this->scheduler->shouldReceive('getScheduledEvents')->never();
        $this->queue->shouldReceive('pingAllTubes')->never();
        $this->database->shouldReceive('check')->never();
        $this->email->shouldReceive('send')->never();
        $this->disk->shouldReceive('check')->never();
        $this->log->shouldReceive('check')->never();

        $response = $this->call('GET', $this->api.'server'.$this->auth);

        $this->assertEquals(json_encode(['server_stats' => ['php' => 'example'], 'eyewitness_version' => Eye::EYE_VERSION, 'application_environment' => 'testing', 'eyewitness_config' => config('eyewitness'), 'request_stats' => ['count' => 5]]), $response->getContent());
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testDisk()
    {
        $this->app['config']->set('eyewitness.monitor_disk', true);

        $this->server->shouldReceive('check')->once()->andReturn(['php' => 'example']);
        $this->disk->shouldReceive('check')->once()->andReturn(['size' => 7]);

        $this->scheduler->shouldReceive('getScheduledEvents')->never();
        $this->queue->shouldReceive('pingAllTubes')->never();
        $this->database->shouldReceive('check')->never();
        $this->request->shouldReceive('check')->never();
        $this->email->shouldReceive('send')->never();
        $this->log->shouldReceive('check')->never();

        $response = $this->call('GET', $this->api.'server'.$this->auth);

        $this->assertEquals(json_encode(['server_stats' => ['php' => 'example'], 'eyewitness_version' => Eye::EYE_VERSION, 'application_environment' => 'testing', 'eyewitness_config' => config('eyewitness'), 'disk_stats' => ['size' => 7]]), $response->getContent());
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testLog()
    {
        $this->app['config']->set('eyewitness.monitor_log', true);

        $this->server->shouldReceive('check')->once()->andReturn(['php' => 'example']);
        $this->log->shouldReceive('check')->once()->andReturn(['tests' => 'ok']);

        $this->scheduler->shouldReceive('getScheduledEvents')->never();
        $this->queue->shouldReceive('pingAllTubes')->never();
        $this->database->shouldReceive('check')->never();
        $this->request->shouldReceive('check')->never();
        $this->email->shouldReceive('send')->never();
        $this->disk->shouldReceive('check')->never();

        $response = $this->call('GET', $this->api.'server'.$this->auth);

        $this->assertEquals(json_encode(['server_stats' => ['php' => 'example'], 'eyewitness_version' => Eye::EYE_VERSION, 'application_environment' => 'testing', 'eyewitness_config' => config('eyewitness'), 'log_stats' => ['tests' => 'ok']]), $response->getContent());
        $this->assertEquals(200, $response->getStatusCode());
    }
}
