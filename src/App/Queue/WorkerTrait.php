<?php

namespace Eyewitness\Eye\App\Queue;

use Illuminate\Support\Facades\Cache;
use Eyewitness\Eye\Eye;

trait WorkerTrait
{
    /**
     * The current queue connection.
     *
     * @var string
     */
    protected $eyeConnection;

    /**
     * The current tube being processed.
     *
     * @var string
     */
    protected $eyeTube;

    /**
     * Extend the worker and place a heartbeat as it is processing. Then
     * simply "feed" the tubes into the next job parent. This provides
     * the exact same functionality, but this way we know exactly
     * which tube is processing the job.
     *
     * @param  \Illuminate\Contracts\Queue\Queue  $connection
     * @param  string  $queue
     * @return \Illuminate\Contracts\Queue\Job|null
     */
    protected function getNextJob($connection, $queue)
    {
        $this->eyeConnection = config('eyewitness.temp_connection_name', 'default');

        if ($this->cache) {
            foreach (explode(',', $queue) as $tube) {
                $this->eyewitnessHeartBeat($tube);
            }
        }

        foreach (explode(',', $queue) as $tube) {
            $job = parent::getNextJob($connection, $tube);

            if (! is_null($job)) {
                $this->eyeTube = $tube;
                return $job;
            }
        }
    }

    /**
     * Check if we have recently pinged Eyewitness for this queue. Only
     * ping if the cache has expired.
     *
     * @param  string  $tube
     * @return void
     */
    protected function eyewitnessHeartBeat($tube)
    {
        if (! $this->cache->has('eyewitness_queue_heartbeat_'.$this->eyeConnection.'_'.$tube)) {
            $this->cache->add('eyewitness_queue_heartbeat_'.$this->eyeConnection.'_'.$tube, 1, 6);
            app(Eye::class)->api()->sendQueuePing($this->eyeConnection, $tube);
        }
    }

    /**
     * Record the end of the job details.
     *
     * @param  float  $startTime
     * @param  string  $tag
     * @return void
     */
    public function recordJobEnd($startTime, $tag)
    {
        $endTime = round((microtime(true) - $startTime)*1000);

        Cache::add('eyewitness_q_process_time_'.$this->eyeConnection.'_'.$this->eyeTube.'_'.$tag, 0, 180);
        Cache::increment('eyewitness_q_process_time_'.$this->eyeConnection.'_'.$this->eyeTube.'_'.$tag, $endTime);
        Cache::add('eyewitness_q_process_count_'.$this->eyeConnection.'_'.$this->eyeTube.'_'.$tag, 0, 180);
        Cache::increment('eyewitness_q_process_count_'.$this->eyeConnection.'_'.$this->eyeTube.'_'.$tag);
    }

    /**
     * Record the exeception count.
     *
     * @param  string  $tag
     * @param  string  $exception
     * @return void
     *
     * @throws \Throwable
     */
    public function recordJobException($tag, $exception)
    {
        Cache::add('eyewitness_q_exception_count_'.$this->eyeConnection.'_'.$this->eyeTube.'_'.$tag, 0, 180);
        Cache::increment('eyewitness_q_exception_count_'.$this->eyeConnection.'_'.$this->eyeTube.'_'.$tag);

        throw $exception;
    }
}
