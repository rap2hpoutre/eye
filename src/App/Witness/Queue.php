<?php

namespace Eyewitness\Eye\App\Witness;

use Illuminate\Queue\QueueManager;
use Exception;

class Queue
{
    /**
     * Get the queue connection configuration.
     *
     * @param  string  $name
     * @return array
     */
    public function getConnectionConfig($connection)
    {
        return app('config')["queue.connections.$connection"];
    }

    /**
     * Get the queue stats for all tubes.
     *
     * @return mixed
     */
    public function allTubeStats()
    {
        $stats = [];

        try {
            foreach (config('eyewitness.queue_tube_list') as $connection => $tubes) {
                foreach ($tubes as $tube) {
                    $stats[] = $this->tubeStats($connection, $tube);
                }
            }
        } catch (Exception $e) {
            //
        }

        return $stats;
    }

    /**
     * Get the queue stats for a specific tube.
     *
     * @param  string  $connection
     * @param  string  $tube
     * @return mixed
     */
    public function tubeStats($connection, $tube)
    {
        $stats['connection'] = $connection;
        $stats['tube'] = $tube;
        $stats['pending_count'] = $this->getPendingJobsCount($connection, $tube);
        $stats['failed_count'] = $this->getFailedJobsCount($connection, $tube);

        return $stats;
    }

    /**
     * Count the number of failed jobs.
     *
     * @param  string  $connection
     * @param  string  $tube
     * @return int
     */
    protected function getFailedJobsCount($connection, $tube)
    {
        return $this->getFailedJobs()->where('queue', $tube)
                                     ->where('connection', $connection)
                                     ->count();
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
                $job->job = isset($payload->displayName) ? $payload->displayName : (isset($payload->job) ? $payload->job : 'Unknown');
                $job->attempts = isset($payload->attempts) ? $payload->attempts : null;
                $job->maxTries = isset($payload->maxTries) ? $payload->maxTries : null;
                $job->timeout = isset($payload->timeout) ? $payload->timeout : null;
                $job->job_id = isset($payload->id) ? $payload->id : null;
                $job->exception = isset($job->exception) ? $job->exception : null;
                $job->payload =isset($payload->data->command) ? json_encode($payload->data->command) : (isset($payload->data) ? json_encode($payload->data) : $job->payload);
                return $job;
            });
            return $list;
        } catch (Exception $e) {
            return collect([]);
        }
    }

    /**
     * Get the number of pending jobs for this queue tube.
     *
     * @param  string  $connection
     * @param  string  $tube
     * @return void
     */
    protected function getPendingJobsCount($connection, $tube)
    {
        $config = $this->getConnectionConfig($connection);
        $driver_class = "\\Eyewitness\\Eye\\App\\Queue\\".ucfirst(strtolower($config['driver'])).'Queue';

        if (class_exists($driver_class)) {
            $qm = app(QueueManager::class)->connection($connection);
            return (new $driver_class($qm, $config))->pendingJobsCount($tube);
        }

        return 0;
    }

    /**
     * Get the resolved name of the queued job class.
     *
     * @param  array  $job
     * @return string
     */
    public function resolveLegacyName($job)
    {
        try {
            $payload = json_decode($job->getRawBody(), true);
            $name = $payload['job'];

            if (! empty($payload['displayName'])) {
                return $payload['displayName'];
            }

            if ($name === 'Illuminate\Queue\CallQueuedHandler@call') {
                return get_class(unserialize($data['data']['command']));
            }

            if ($name === 'Illuminate\Events\CallQueuedHandler@call') {
                return $payload['data']['class'].'@'.$payload['data']['method'];
            }
        } catch (Exception $e) {
            $name = 'Unknown';
        }

        return $name;
    }
}

