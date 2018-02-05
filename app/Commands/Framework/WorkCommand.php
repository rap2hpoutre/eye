<?php

namespace Eyewitness\Eye\Commands\Framework;

use Eyewitness\Eye\Repo\Queue;
use Illuminate\Queue\Console\WorkCommand as OriginalWorkCommand;

class WorkCommand extends OriginalWorkCommand
{
    /**
     * Here we intercept the run command and make sure we create a
     * model to represent each queue tube for future tracking.
     *
     * @param  string  $connection
     * @param  string  $queue
     * @return array
     */
    protected function runWorker($connection, $queue)
    {
        foreach (explode(',', $queue) as $tube) {
            $this->worker->eyeQueues[] = Queue::firstOrCreate(['connection' => $connection,
                                                               'tube' => $tube,
                                                               'driver' => $this->getDriverName($connection),
                                                               'healthy' => 1]);
        }

        return parent::runWorker($connection, $queue);
    }

    /**
     * Get the underlying driver name for the queue.
     *
     * @param  string  $connection
     * @return string
     */
    protected function getDriverName($connection)
    {
        return config("queue.connections.$connection.driver", 'unknown');
    }
}
