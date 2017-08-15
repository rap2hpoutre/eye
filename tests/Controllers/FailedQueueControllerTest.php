<?php

use Eyewitness\Eye\App\Witness\Queue;

class FailedQueueControllerTest extends TestCase
{
    public function test_controller_requires_authentication()
    {
        $response = $this->call('GET', $this->api.'failed_queue');
        $this->assertEquals(json_encode(['error' => 'Unauthorized']), $response->getContent());
        $this->assertEquals(401, $response->getStatusCode());
    }

    public function test_failed_queue_honours_config()
    {
        $this->app['config']->set('eyewitness.routes_queue', false);

        $response = $this->call('GET', $this->api.'failed_queue'.$this->auth);
        $this->assertEquals(json_encode(['error' => 'The queue route is disabled on the server']), $response->getContent());
        $this->assertEquals(405, $response->getStatusCode());

        $response = $this->call('GET', $this->api.'failed_queue/delete/all'.$this->auth);
        $this->assertEquals(json_encode(['error' => 'The queue route is disabled on the server']), $response->getContent());
        $this->assertEquals(405, $response->getStatusCode());

        $response = $this->call('GET', $this->api.'failed_queue/delete/1'.$this->auth);
        $this->assertEquals(json_encode(['error' => 'The queue route is disabled on the server']), $response->getContent());
        $this->assertEquals(405, $response->getStatusCode());

        $response = $this->call('GET', $this->api.'failed_queue/retry/all'.$this->auth);
        $this->assertEquals(json_encode(['error' => 'The queue route is disabled on the server']), $response->getContent());
        $this->assertEquals(405, $response->getStatusCode());

        $response = $this->call('GET', $this->api.'failed_queue/retry/1'.$this->auth);
        $this->assertEquals(json_encode(['error' => 'The queue route is disabled on the server']), $response->getContent());
        $this->assertEquals(405, $response->getStatusCode());
    }

    public function test_index_gets_failed_jobs()
    {
        $queueMock = Mockery::mock(Queue::class);
        $this->app->instance(Queue::class, $queueMock);
        $queueMock->shouldReceive('getFailedJobs')->once()->andReturn(['example' => 'job']);

        $response = $this->call('GET', $this->api.'failed_queue'.$this->auth);

        $this->assertEquals(json_encode(['data' => ['example' => 'job']]), $response->getContent());
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_delete_failed_job_is_successful()
    {
        $m = Mockery::mock(Illuminate\Queue\Failed\NullFailedJobProvider::class);

        $this->app->singleton('queue.failer', function ($app) use ($m) {
            return $m;
        });

        $m->shouldReceive('forget')
          ->with(1)
          ->once()
          ->andReturn(true);

        $response = $this->call('GET', $this->api.'failed_queue/delete/1'.$this->auth);

        $this->assertEquals(json_encode(['msg' => 'Success']), $response->getContent());
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_delete_failed_job_that_does_not_exist_generates_error()
    {
        $m = Mockery::mock(Illuminate\Queue\Failed\NullFailedJobProvider::class);

        $this->app->singleton('queue.failer', function ($app) use ($m) {
            return $m;
        });

        $m->shouldReceive('forget')
          ->with(1)
          ->once()
          ->andReturn(false);

        $response = $this->call('GET', $this->api.'failed_queue/delete/1'.$this->auth);

        $this->assertEquals(json_encode(['error' => 'Could not find that log id to delete']), $response->getContent());
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function test_delete_failed_job_catches_exception()
    {
        $m = Mockery::mock(Illuminate\Queue\Failed\NullFailedJobProvider::class);

        $this->app->singleton('queue.failer', function ($app) use ($m) {
            return $m;
        });

        $m->shouldReceive('forget')
          ->with(1)
          ->once()
          ->andThrow(new Exception('my error'));

        $response = $this->call('GET', $this->api.'failed_queue/delete/1'.$this->auth);

        $this->assertEquals(json_encode(['error' => 'my error']), $response->getContent());
        $this->assertEquals(500, $response->getStatusCode());
    }

    public function test_delete_all_failed_job_is_successful()
    {
        $m = Mockery::mock(Illuminate\Queue\Failed\NullFailedJobProvider::class);

        $this->app->singleton('queue.failer', function ($app) use ($m) {
            return $m;
        });

        $m->shouldReceive('flush')
          ->once()
          ->andReturn(true);

        $response = $this->call('GET', $this->api.'failed_queue/delete/all'.$this->auth);

        $this->assertEquals(json_encode(['msg' => 'Success']), $response->getContent());
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_delete_all_failed_job_that_does_not_exist_is_ok()
    {
        $m = Mockery::mock(Illuminate\Queue\Failed\NullFailedJobProvider::class);

        $this->app->singleton('queue.failer', function ($app) use ($m) {
            return $m;
        });

        $m->shouldReceive('flush')
          ->once()
          ->andReturn(false);

        $response = $this->call('GET', $this->api.'failed_queue/delete/all'.$this->auth);

        $this->assertEquals(json_encode(['msg' => 'Success']), $response->getContent());
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_delete_all_failed_job_catches_exception()
    {
        $m = Mockery::mock(Illuminate\Queue\Failed\NullFailedJobProvider::class);

        $this->app->singleton('queue.failer', function ($app) use ($m) {
            return $m;
        });

        $m->shouldReceive('flush')
          ->once()
          ->andThrow(new Exception('my error'));

        $response = $this->call('GET', $this->api.'failed_queue/delete/all'.$this->auth);

        $this->assertEquals(json_encode(['error' => 'my error']), $response->getContent());
        $this->assertEquals(500, $response->getStatusCode());
    }

    public function test_retry_job_is_successful()
    {
        Artisan::shouldReceive('call')
               ->with('queue:retry', ['id' => [1]])
               ->once()
               ->andReturn(true);

        $response = $this->call('GET', $this->api.'failed_queue/retry/1'.$this->auth);

        $this->assertEquals(json_encode(['msg' => 'Success']), $response->getContent());
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_retry_job_handles_exception()
    {
        Artisan::shouldReceive('call')
               ->with('queue:retry', ['id' => [1]])
               ->once()
               ->andThrow(new Exception('my error'));

        $response = $this->call('GET', $this->api.'failed_queue/retry/1'.$this->auth);

        $this->assertEquals(json_encode(['error' => 'my error']), $response->getContent());
        $this->assertEquals(500, $response->getStatusCode());
    }

    public function test_retry_all_job_is_successful()
    {
        Artisan::shouldReceive('call')
               ->with('queue:retry', ['id' => ['all']])
               ->once()
               ->andReturn(true);

        $response = $this->call('GET', $this->api.'failed_queue/retry/all'.$this->auth);

        $this->assertEquals(json_encode(['msg' => 'Success']), $response->getContent());
        $this->assertEquals(200, $response->getStatusCode());
    }
}
