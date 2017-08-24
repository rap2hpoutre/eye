<?php

namespace Eyewitness\Eye\App\Witness;

use Illuminate\Support\Facades\Queue as QueueFacade;
use Eyewitness\Eye\App\Jobs\SonarLegacy50;
use Eyewitness\Eye\App\Jobs\SonarLegacy;
use Illuminate\Support\Facades\Cache;
use Eyewitness\Eye\App\Jobs\Sonar;
use Illuminate\Queue\QueueManager;
use Eyewitness\Eye\Eye;
use Exception;

class Queue
{
    /**
     * Get the checks for all tubes.
     *
     * @return array
     */
    public function check()
    {
        $stats = [];

        try {
            foreach ($this->tubes() as $connection => $tubes) {
                foreach ($tubes as $tube) {
                    $stats[$connection][$tube] = $this->tubeStats($connection, $tube);
                }
            }
        } catch (Exception $e) {
            //
        }

        return $stats;
    }

    /**
     * Send a sonar tracking job on the queue for each connection and tube.
     *
     * @return void
     */
    public function deploySonar()
    {
        foreach ($this->tubes() as $connection => $tubes) {
            foreach ($tubes as $tube) {
                if (! Cache::has('eyewitness_q_sonar_deployed_'.$connection.'_'.$tube)) {
                    if (laravel_version_is('>=', '5.2.0')) {
                        QueueFacade::connection($connection)->pushOn($tube, new Sonar($connection, $tube));
                    } elseif (laravel_version_is('<', '5.1.0')) {
                        QueueFacade::connection($connection)->pushOn($tube, new SonarLegacy50($connection, $tube));
                    } else {
                        QueueFacade::connection($connection)->pushOn($tube, new SonarLegacy($connection, $tube));
                    }
                    Cache::put('eyewitness_q_sonar_deployed_'.$connection.'_'.$tube, time(), 180);
                }
            }
        }
    }

    /**
     * Get the queue stats for a specific tube.
     *
     * @param  string  $connection
     * @param  string  $tube
     * @return array
     */
    public function tubeStats($connection, $tube)
    {
        $stats['failed_count'] = $this->getFailedJobsCount($connection, $tube);
        $stats['pending_count'] = $this->getPendingJobsCount($connection, $tube);
        $stats['process_time'] = Cache::pull('eyewitness_q_process_time_'.$connection.'_'.$tube);
        $stats['process_count'] = Cache::pull('eyewitness_q_process_count_'.$connection.'_'.$tube);
        $stats['exception_count'] = Cache::pull('eyewitness_q_exception_count_'.$connection.'_'.$tube);
        $stats['wait_time'] = Cache::pull('eyewitness_q_wait_time_'.$connection.'_'.$tube);
        $stats['wait_count'] = Cache::pull('eyewitness_q_wait_count_'.$connection.'_'.$tube);
        $stats['idle_time'] = Cache::pull('eyewitness_q_idle_time_'.$connection.'_'.$tube);
        $stats['wait_time'] = Cache::pull('eyewitness_q_current_wait_time_'.$connection.'_'.$tube);
        $stats['sonar_deployed'] = time()-Cache::get('eyewitness_q_sonar_deployed_'.$connection.'_'.$tube, time());

        return $stats;
    }

    /**
     * Handle a failing queue notification.
     *
     * @param  string  $connection
     * @param  string  $name
     * @param  string  $tube
     */
    public function failedQueue($connection, $name, $tube)
    {
        if (Cache::has('eyewitness_debounce_failed_queue')) {
            return;
        }

        Cache::add('eyewitness_debounce_failed_queue', 1, 3);

        app(Eye::class)->api()->sendQueueFailingPing($connection, $name, $tube);
    }

    /**
     * Get a list of failed jobs.
     *
     * @return collection
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
                $job->payload = isset($payload->data->command) ? json_encode($payload->data->command) : (isset($payload->data) ? json_encode($payload->data) : $job->payload);
                return $job;
            });
            return $list;
        } catch (Exception $e) {
            return collect([]);
        }
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
     * Get all list of all tubes.
     *
     * @return array
     */
    public function tubes()
    {
        return config('eyewitness.queue_tube_list');
    }
}

