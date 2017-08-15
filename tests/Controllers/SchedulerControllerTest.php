<?php

use Illuminate\Support\Facades\Cache;

class SchedulerControllerTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function test_scheduler_controller_requires_authentication()
    {
        $response = $this->call('GET', $this->api.'scheduler/event/run');
        $this->assertEquals(json_encode(['error' => 'Unauthorized']), $response->getContent());
        $this->assertEquals(401, $response->getStatusCode());
    }

    public function test_scheduler_honours_config()
    {
        $this->app['config']->set('eyewitness.routes_scheduler', false);

        $response = $this->call('GET', $this->api.'scheduler/event/run'.$this->auth);
        $this->assertEquals(json_encode(['error' => 'The scheduler route is disabled on the server']), $response->getContent());
        $this->assertEquals(405, $response->getStatusCode());
    }

    public function test_scheduler_requires_and_validates_id()
    {
        $response = $this->call('GET', $this->api.'scheduler/event/run'.$this->auth);
        $this->assertEquals(302, $response->getStatusCode());
    }

    public function test_adds_new_mutex_to_empty_array()
    {
        $mutex = str_random(50);

        Cache::shouldReceive('has')
             ->once()
             ->with('eyewitness_scheduler_adhoc')
             ->andReturn(false);

        Cache::shouldReceive('put')
             ->once()
             ->with('eyewitness_scheduler_adhoc', json_encode([$mutex => $mutex]), 3);

        $response = $this->call('GET', $this->api.'scheduler/event/run'.$this->auth.'&id='.$mutex);

        $this->assertEquals(json_encode(['msg' => 'Success']), $response->getContent());
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_adds_new_mutex_to_existing_array()
    {
        $mutex = str_random(50);

        Cache::shouldReceive('has')
             ->once()
             ->with('eyewitness_scheduler_adhoc')
             ->andReturn(true);

        Cache::shouldReceive('get')
             ->once()
             ->with('eyewitness_scheduler_adhoc')
             ->andReturn(json_encode(['original' => 'original']));

        Cache::shouldReceive('put')
             ->once()
             ->with('eyewitness_scheduler_adhoc', json_encode(['original' => 'original', $mutex => $mutex]), 3);

        $response = $this->call('GET', $this->api.'scheduler/event/run'.$this->auth.'&id='.$mutex);

        $this->assertEquals(json_encode(['msg' => 'Success']), $response->getContent());
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_adds_mutex_to_empty_array()
    {
        $mutex = str_random(50);

        Cache::shouldReceive('has')
             ->once()
             ->with('eyewitness_scheduler_forget_mutex')
             ->andReturn(false);

        Cache::shouldReceive('put')
             ->once()
             ->with('eyewitness_scheduler_forget_mutex', json_encode([$mutex => $mutex]), 3);

        $response = $this->call('GET', $this->api.'scheduler/event/forget_mutex'.$this->auth.'&id='.$mutex);

        $this->assertEquals(json_encode(['msg' => 'Success']), $response->getContent());
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_adds_mutex_to_existing_array()
    {
        $mutex = str_random(50);

        Cache::shouldReceive('has')
             ->once()
             ->with('eyewitness_scheduler_forget_mutex')
             ->andReturn(true);

        Cache::shouldReceive('get')
             ->once()
             ->with('eyewitness_scheduler_forget_mutex')
             ->andReturn(json_encode(['original' => 'original']));

        Cache::shouldReceive('put')
             ->once()
             ->with('eyewitness_scheduler_forget_mutex', json_encode(['original' => 'original', $mutex => $mutex]), 3);

        $response = $this->call('GET', $this->api.'scheduler/event/forget_mutex'.$this->auth.'&id='.$mutex);

        $this->assertEquals(json_encode(['msg' => 'Success']), $response->getContent());
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_pause_scheduler()
    {
        $mutex = str_random(50);

        Cache::shouldReceive('forever')
             ->once()
             ->with('eyewitness_scheduler_mutex_'.$mutex, 1);

        $response = $this->call('GET', $this->api.'scheduler/event/pause'.$this->auth.'&id='.$mutex);

        $this->assertEquals(json_encode(['msg' => 'Success']), $response->getContent());
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_resume_scheduler()
    {
        $mutex = str_random(50);

        Cache::shouldReceive('forget')
             ->once()
             ->with('eyewitness_scheduler_mutex_'.$mutex);

        $response = $this->call('GET', $this->api.'scheduler/event/resume'.$this->auth.'&id='.$mutex);

        $this->assertEquals(json_encode(['msg' => 'Success']), $response->getContent());
        $this->assertEquals(200, $response->getStatusCode());
    }
}
