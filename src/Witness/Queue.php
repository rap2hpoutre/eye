<?php

namespace Eyewitness\Eye\Witness;

use Illuminate\Database\QueryException;
use Illuminate\Queue\QueueManager;
use ReflectionClass;

class Queue
{
    /**
     * Get the queue driver name.
     *
     * @return string
     */
    public function driverName()
    {
        return app(QueueManager::class)->getDefaultDriver();
    }

    /**
     * Get the queue stats for all tubes.
     *
     * @return mixed
     */
    public function allTubeStats()
    {
        $stats = [];

        foreach (config('eyewitness.queue_tube_list') as $tube) {
            $stats[] = $this->tubeStats($tube);
        }

        return $stats;
    }

    /**
     * Get the queue stats for a specific tube.
     *
     * @param  string  $tube
     * @return mixed
     */
    public function tubeStats($tube)
    {
        $stats['tube_name'] = $tube;
        $stats['pending_count'] = $this->getPendingJobsCount($tube);
        $stats['failed_count'] = $this->getFailedJobsCount($tube);

        return $stats;
    }

    /**
     * Get a list of failed jobs.
     *
     * @return mixed
     */
    public function getFailedJobs()
    {
        try {
            $list = collect(app('queue.failer')->all());
            $list->map(function ($job) {
                $payload = json_decode($job->payload);
                $job->job = isset($payload->job) ? $payload->job : 'Unknown';
                return $job;
            });
            return $list;
        } catch (QueryException $e) {
            return collect([]);
        }
    }

    /**
     * Count the number of failed jobs.
     *
     * @param  string  $tube
     * @return int
     */
    protected function getFailedJobsCount($tube)
    {
        return $this->getFailedJobs()->where('queue', $tube)->count();
    }

    /**
     * Get the number of pending jobs for this queue tube.
     *
     * @param  string  $tube
     * @return void
     */
    protected function getPendingJobsCount($tube)
    {
        $qm = app(QueueManager::class);
        $this->driver = (new ReflectionClass($qm->connection()))->getShortName();
        $driver_class = "\\Eyewitness\\Eye\\Queue\\$this->driver";

        if (class_exists($driver_class)) {
            return (new $driver_class($qm->connection()))->pendingJobsCount($tube);
        }
        
        return 0;
    }
}

