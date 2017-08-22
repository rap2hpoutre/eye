<?php

namespace Eyewitness\Eye\App\Queue;

use Illuminate\Queue\Worker as OriginalWorker;
use Illuminate\Contracts\Queue\Job;
use Exception;
use Throwable;

class WorkerLegacy extends OriginalWorker
{
    use WorkerTrait;

    /**
     * Process a given job from the queue.
     *
     * @param  string  $connection
     * @param  \Illuminate\Contracts\Queue\Job  $job
     * @param  int  $maxTries
     * @param  int  $delay
     * @return array|null
     *
     * @throws \Throwable
     */
    public function process($connection, Job $job, $maxTries = 0, $delay = 0)
    {
        $start_time = microtime(true);
        $tag = gmdate('Y_m_d_H');

        try {
            parent::process($connection, $job, $maxTries, $delay);
        } catch (Exception $e) {
            $this->recordJobException($tag, $e);
        } catch (Throwable $e) {
            $this->recordJobException($tag, $e);
        } finally {
            $this->recordJobEnd($start_time, $tag);
        }
    }
}
