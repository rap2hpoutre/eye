<?php

namespace Eyewitness\Eye\App\Queue;

use Illuminate\Queue\Worker as OriginalWorker;
use Illuminate\Queue\WorkerOptions;
use Exception;
use Throwable;

class Worker extends OriginalWorker
{
    use WorkerTrait;

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
        $startTime = microtime(true);
        $tag = gmdate('Y_m_d_H');

        try {
            parent::process($connectionName, $job, $options);
        } catch (Exception $e) {
            $this->recordJobException($tag, $e);
        } catch (Throwable $e) {
            $this->recordJobException($tag, $e);
        } finally {
            $this->recordJobEnd($startTime, $tag);
        }
    }
}
