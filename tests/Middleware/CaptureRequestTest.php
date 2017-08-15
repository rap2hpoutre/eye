<?php

use Eyewitness\Eye\App\Http\Middleware\CaptureRequest;

class CaptureRequestTest extends TestCase
{
    protected $capture_request;

    protected $tag;

    public function setUp()
    {
        parent::setUp();

        $this->capture_request = new CaptureRequest;
        $this->tag = gmdate('Y_m_d_H');
    }

    public function test_cache_minutes()
    {
        $this->assertEquals(180, $this->capture_request->cacheMinutes);
    }

    public function test_terminate_calls_request_and_cycle_increments()
    {
        $request = new \Illuminate\Http\Request;

        $m = Mockery::mock(CaptureRequest::class)->makePartial();
        $m->shouldReceive('getCycleTime')->once()->with($request)->andReturn(5);

        Cache::shouldReceive('add')
             ->with('eyewitness_request_count_'.$this->tag, 0, 180)
             ->once();

        Cache::shouldReceive('increment')
             ->with('eyewitness_request_count_'.$this->tag, 1)
             ->once();

        Cache::shouldReceive('add')
             ->with('eyewitness_total_execution_time_'.$this->tag, 0, 180)
             ->once();

        Cache::shouldReceive('increment')
             ->with('eyewitness_total_execution_time_'.$this->tag, 5)
             ->once();

        $m->terminate($request, null);
    }

    public function test_get_cycle_time()
    {
        $m = new CaptureRequest;
        $request = new Illuminate\Http\Request;

        $this->assertGreaterThan(1, $m->getCycleTime($request));
        $this->assertInternalType("int", $m->getCycleTime($request));
    }

}
