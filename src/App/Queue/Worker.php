<?php

namespace Eyewitness\Eye\App\Queue;

use Illuminate\Queue\Worker as OriginalWorker;
use Illuminate\Support\Facades\Cache;
use Illuminate\Queue\WorkerOptions;
use Eyewitness\Eye\Eye;
use Exception;
use Throwable;

class Worker extends OriginalWorker
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
     * We wrap the standard job processor, and add our tracking code around it.
     *
     * @param  string  $connectionName
     * @param  \Illuminate\Contracts\Queue\Job  $job
     * @param  \Illuminate\Queue\WorkerOptions  $options
     * @return void
     *
     * @throws \Throwable
     */
    public function process($connectionName, $job, WorkerOptions $options)
    {
        $start_time = microtime(true);
        $tag = gmdate('Y_m_d_H');

        try {
            parent::process($connectionName, $job, $options);
        } catch (Exception $e) {
            $this->recordJobException($tag, $e);
        } catch (Throwable $e) {
            $this->recordJobException($tag, $e);
        } finally {
            $end_time = round((microtime(true) - $start_time)*1000);
            Cache::add('eyewitness_q_time_'.$this->eyeConnection.'_'.$this->eyeTube.'_'.$tag, 0, 180);
            Cache::increment('eyewitness_q_time_'.$this->eyeConnection.'_'.$this->eyeTube.'_'.$tag, $end_time);
            Cache::add('eyewitness_q_count_'.$this->eyeConnection.'_'.$this->eyeTube.'_'.$tag, 0, 180);
            Cache::increment('eyewitness_q_count_'.$this->eyeConnection.'_'.$this->eyeTube.'_'.$tag);
        }
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
        Cache::add('eyewitness_q_e_count_'.$this->eyeConnection.'_'.$this->eyeTube.'_'.$tag, 0, 180);
        Cache::increment('eyewitness_q_e_count_'.$this->eyeConnection.'_'.$this->eyeTube.'_'.$tag);
        throw $exception;
    }
}
