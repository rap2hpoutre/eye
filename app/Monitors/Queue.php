<?php

namespace Eyewitness\Eye\Monitors;

use Exception;
use Carbon\Carbon;
use Illuminate\Queue\QueueManager;
use Eyewitness\Eye\Repo\Queue as QueueRepo;
use Eyewitness\Eye\Queue\Jobs\Sonar;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Queue as QueueFacade;
use Eyewitness\Eye\Repo\History\Queue as History;
use Eyewitness\Eye\Queue\Jobs\SonarLegacy;
use Eyewitness\Eye\Notifications\Messages\Queue\WaitOk;
use Eyewitness\Eye\Notifications\Messages\Queue\Failed;
use Eyewitness\Eye\Notifications\Messages\Queue\Online;
use Eyewitness\Eye\Notifications\Messages\Queue\Offline;
use Eyewitness\Eye\Notifications\Messages\Queue\WaitLong;
use Eyewitness\Eye\Notifications\Messages\Queue\FailedCountOk;
use Eyewitness\Eye\Notifications\Messages\Queue\PendingCountOk;
use Eyewitness\Eye\Notifications\Messages\Queue\FailedCountExceeded;
use Eyewitness\Eye\Notifications\Messages\Queue\PendingCountExceeded;


class Queue extends BaseMonitor
{
    /**
     * Poll the Queue monitor for all checks.
     *
     * @return void
     */
    public function poll()
    {
        try {
            foreach ($this->getMonitoredQueues() as $queue) {
                $this->storeTubeStats($queue);
                $this->deploySonar($queue);

                if (! $this->isQueueOnline($queue)) {
                    continue;
                }

                if (! $this->isWaitTimeOk($queue)) {
                    continue;
                }

                if (! $this->isPendingCountOk($queue)) {
                    continue;
                }

                if (! $this->isFailedCountOk($queue)) {
                    continue;
                }
            }
        } catch (Exception $e) {
            $this->eye->logger()->error('Error during queue poll process', $e);
        }
    }

    /**
     * Send a sonar tracking job on the queue for each connection and tube.
     *
     * @param  \Eyewitness\Eye\Repo\Queue  $queue
     * @return void
     */
    public function deploySonar(QueueRepo $queue)
    {
        if (! Cache::has('eyewitness_q_sonar_deployed_'.$queue->id)) {
            Cache::put('eyewitness_q_sonar_deployed_'.$queue->id, time(), 180);

            if ($this->eye->laravelVersionIs('>=', '5.2.0')) {
                QueueFacade::connection($queue->connection)->pushOn($queue->tube, new Sonar($queue->id, $queue->connection, $queue->tube));
            } else {
                QueueFacade::connection($queue->connection)->pushOn($queue->tube, new SonarLegacy($queue->id, $queue->connection, $queue->tube));
            }
        }
    }

    /**
     * Get the queue stats for a specific tube.
     *
     * @param  \Eyewitness\Eye\Repo\Queue  $queue
     * @return void
     */
    public function storeTubeStats(QueueRepo $queue)
    {
        $history = History::firstOrNew(['date' => date('Y-m-d'),
                                        'hour' => date('H'),
                                        'queue_id' => $queue->id]);

        $history->pending_count = $this->getPendingJobsCount($queue);
        $history->failed_count = $this->getFailedJobsCount($queue);

        $history->exception_count += Cache::pull('eyewitness_q_exception_count_'.$queue->id);
        $history->process_count += Cache::pull('eyewitness_q_process_count_'.$queue->id);
        $history->process_time += Cache::pull('eyewitness_q_process_time_'.$queue->id);
        $history->sonar_time += Cache::pull('eyewitness_q_sonar_time_'.$queue->id);
        $history->sonar_count += Cache::pull('eyewitness_q_sonar_count_'.$queue->id);
        $history->idle_time += Cache::pull('eyewitness_q_idle_time_'.$queue->id);

        $deploy = Cache::get('eyewitness_q_sonar_deployed_'.$queue->id, null);
        if (is_null($deploy)) {
            $history->sonar_deployed = null;
        } else {
            $history->sonar_deployed = time()-$deploy;
        }

        $history->save();
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

        $this->eye->notifier()->alert(new Failed(['connection' => $connection,
                                                  'tube' => $tube,
                                                  'job' => $name]));
    }

    /**
     * Get a list of failed jobs for a given queue.
     *
     * @param  \Eyewitness\Eye\Repo\Queue  $queue
     * @return collection
     */
    public function getFailedJobs($queue)
    {
        try {
            $list = collect(app('queue.failer')->all())->where('queue', $queue->tube)
                                                       ->where('connection', $queue->connection);

            $list->map(function ($job) {
                $job = $this->mapJob($job);
            });

            return $list;
        } catch (Exception $e) {
            $this->eye->logger()->error('Unable to get Queue Failed Jobs', $e, $queue->id);
        }

        return collect([]);
    }

    /**
     * Get a specific failed jobs for a given queue.
     *
     * @param  integer  $job_id
     * @return array|null
     */
    public function getFailedJob($job_id)
    {
        try {
            $job = app('queue.failer')->find($job_id);

            $job = $this->mapJob($job);

            return $job;
        } catch (Exception $e) {
            $this->eye->logger()->error('Unable to get Queue Failed Job', $e, $job_id);
        }

        return null;
    }

    /**
     * Count the number of failed jobs.
     *
     * @param  \Eyewitness\Eye\Repo\Queue  $queue
     * @return int
     */
    public function getFailedJobsCount($queue)
    {
        return $this->getFailedJobs($queue)->count();
    }

    /**
     * Get the number of pending jobs for this queue tube.
     *
     * @param  \Eyewitness\Eye\Repo\Queue  $queue
     * @return int
     */
    public function getPendingJobsCount($queue)
    {
        $driver_class = "\\Eyewitness\\Eye\\Queue\\Handlers\\".ucfirst(strtolower($queue->driver)).'Queue';

        if (! class_exists($driver_class)) {
            $this->eye->logger()->error('Queue Driver does not exist', $driver_class);
            return 0;
        }

        try {
            $qm = app(QueueManager::class)->connection($queue->connection);

            return (new $driver_class($qm, $queue))->pendingJobsCount($queue->tube);
        } catch (Exception $e) {
            $this->eye->logger()->debug('Unable to find Queue connection', ['exception' => $e->getMessage(), 'connection' => $queue->connection]);
        }

        return -1;
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
            $this->eye->logger()->error('Unable to resolve Legacy Name', $e);
            $name = 'Unknown';
        }

        return $name;
    }

    /**
     * Get all list of all queue tubes.
     *
     * @return \Eyewitness\Eye\Repo\Queue
     */
    public function getMonitoredQueues()
    {
        return QueueRepo::all();
    }

    /**
     * Check if the queue is online.
     *
     * @param  \Eyewitness\Eye\Repo\Queue  $queue
     * @return bool
     */
    public function isQueueOnline($queue)
    {
        if ($queue->alert_heartbeat_greater_than < 1) {
            return true;
        }

        if (Carbon::now()->diffInSeconds($queue->last_heartbeat) <= $queue->alert_heartbeat_greater_than) {
            if ($this->eye->status()->isSick('queue_online_'.$queue->id)) {
                $this->eye->notifier()->alert(new Online(['queue' => $queue]));
            }

            $this->eye->status()->setHealthy('queue_online_'.$queue->id);

            return true;
        }

        if ($this->eye->status()->isHealthy('queue_online_'.$queue->id)) {
            $this->eye->notifier()->alert(new Offline(['queue' => $queue]));
        }

        $this->eye->status()->setSick('queue_online_'.$queue->id);

        return false;
    }

    /**
     * Check if the queue wait time is ok.
     *
     * @param  \Eyewitness\Eye\Repo\Queue  $queue
     * @return bool
     */
    public function isWaitTimeOk($queue)
    {
        if ($queue->alert_wait_time_greater_than < 1) {
            return true;
        }

        if ($queue->current_wait_time <= $queue->alert_wait_time_greater_than) {
            if ($this->eye->status()->isSick('queue_wait_time_'.$queue->id)) {
                $this->eye->notifier()->alert(new WaitOk(['queue' => $queue]));
            }

            $this->eye->status()->setHealthy('queue_wait_time_'.$queue->id);

            return true;
        }

        if ($this->eye->status()->isHealthy('queue_wait_time_'.$queue->id)) {
            $this->eye->notifier()->alert(new WaitLong(['queue' => $queue]));
        }

        $this->eye->status()->setSick('queue_wait_time_'.$queue->id);

        return false;
    }

    /**
     * Check if the queue failed count is ok.
     *
     * @param  \Eyewitness\Eye\Repo\Queue  $queue
     * @return bool
     */
    public function isFailedCountOk($queue)
    {
        if ($queue->alert_failed_jobs_greater_than < 1) {
            return true;
        }

        if ($this->getFailedJobsCount($queue) <= $queue->alert_failed_jobs_greater_than) {
            if ($this->eye->status()->isSick('queue_failed_jobs_'.$queue->id)) {
                $this->eye->notifier()->alert(new FailedCountOk(['queue' => $queue, 'failed_job_count' => $this->getFailedJobsCount($queue)]));
            }

            $this->eye->status()->setHealthy('queue_failed_jobs_'.$queue->id);

            return true;
        }

        if ($this->eye->status()->isHealthy('queue_failed_jobs_'.$queue->id)) {
            $this->eye->notifier()->alert(new FailedCountExceeded(['queue' => $queue, 'failed_job_count' => $this->getFailedJobsCount($queue)]));
        }

        $this->eye->status()->setSick('queue_failed_jobs_'.$queue->id);

        return false;
    }

    /**
     * Check if the queue pending count is ok.
     *
     * @param  \Eyewitness\Eye\Repo\Queue  $queue
     * @return bool
     */
    public function isPendingCountOk($queue)
    {
        if ($queue->alert_pending_jobs_greater_than < 1) {
            return true;
        }

        if ($this->getPendingJobsCount($queue) <= $queue->alert_pending_jobs_greater_than) {
            if ($this->eye->status()->isSick('queue_pending_jobs_'.$queue->id)) {
                $this->eye->notifier()->alert(new PendingCountOk(['queue' => $queue, 'pending_job_count' => $this->getPendingJobsCount($queue)]));
            }

            $this->eye->status()->setHealthy('queue_pending_jobs_'.$queue->id);

            return true;
        }

        if ($this->eye->status()->isHealthy('queue_pending_jobs_'.$queue->id)) {
            $this->eye->notifier()->alert(new PendingCountExceeded(['queue' => $queue, 'pending_job_count' => $this->getPendingJobsCount($queue)]));
        }

        $this->eye->status()->setSick('queue_pending_jobs_'.$queue->id);

        return false;
    }

    /**
     * Map the job to a standard format.
     *
     * @param  array $job
     * @return mised
     */
    protected function mapJob($job)
    {
        try {
            $payload = json_decode($job->payload);
            $job->job = isset($payload->displayName) ? $payload->displayName : (isset($payload->job) ? $payload->job : 'Unknown');
            $job->attempts = isset($payload->attempts) ? $payload->attempts : null;
            $job->maxTries = isset($payload->maxTries) ? $payload->maxTries : null;
            $job->timeout = isset($payload->timeout) ? $payload->timeout : null;
            $job->job_id = isset($payload->id) ? $payload->id : null;

            if (config('eyewitness.allow_failed_job_exception_data', true)) {
                $job->exception = isset($job->exception) ? $job->exception : null;
            } else {
                $job->exception = null;
            }

            if (config('eyewitness.allow_failed_job_payload_data', true)) {
                $job->payload = isset($payload->data->command) ? $payload->data->command : (isset($payload->data) ? $payload->data : $job->payload);
                $job->payload = get_object_vars(unserialize($job->payload));
            } else {
                $job->payload = null;
            }

            return $job;
        } catch (Exception $e) {
            $this->eye->logger()->error('Unable to map the Failed Job', $e);
        }

        return null;
    }
}
