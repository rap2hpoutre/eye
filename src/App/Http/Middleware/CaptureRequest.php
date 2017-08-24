<?php

namespace Eyewitness\Eye\App\Http\Middleware;

use Illuminate\Support\Facades\Cache;
use Closure;

class CaptureRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        return $next($request);
    }

    /**
     * Handle the terminating request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Response  $response
     * @return void
     */
    public function terminate($request, $response)
    {
        $this->incrementRequestCount();
        $this->incrementCycleTime($this->getCycleTime($request));
    }

    /**
     * Increment the total request count.
     *
     * @return void
     */
    protected function incrementRequestCount()
    {
        Cache::add('eyewitness_request_count', 0, 180);
        Cache::increment('eyewitness_request_count', 1);
    }

    /**
     * Increment the total cycle time count.
     *
     * @param  integer $cycleTime
     * @return void
     */
    protected function incrementCycleTime($cycleTime)
    {
        Cache::add('eyewitness_total_execution_time', 0, 180);
        Cache::increment('eyewitness_total_execution_time', $cycleTime);
    }

    /**
     * Get the start time for this request cycle. Increments must be "whole" numbers
     * so we also return an integer.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return integer
     */
    public function getCycleTime($request)
    {
        if (! defined('LARAVEL_START')) {
            return (int) round((microtime(true) - $request->server('REQUEST_TIME_FLOAT'))*1000);
        }

        return (int) round((microtime(true) - LARAVEL_START)*1000);
    }

}
