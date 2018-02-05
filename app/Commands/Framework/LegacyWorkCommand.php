<?php

namespace Eyewitness\Eye\Commands\Framework;

use Eyewitness\Eye\Repo\Queue;
use Illuminate\Queue\Console\WorkCommand as OriginalWorkCommand;

class LegacyWorkCommand extends OriginalWorkCommand
{
    /**
     * This extends the Work Command for applications running Laravel
     * version < 5.3.0.
     *
     * These were changes added to 5.3 onwards, so we are effectively just
     * back porting the features to ensure consistency and adding the
     * ability to effectively monitor what is required.
     *
     * We also intercept the run command and make sure we create a
     * model to represent each queue tube for future tracking.
     *
     * @param  string  $connection
     * @param  string  $queue
     * @param  int  $delay
     * @param  int  $memory
     * @param  bool  $daemon
     * @return array
     */
    protected function runWorker($connection, $queue, $delay, $memory, $daemon = false)
    {
        if (! $daemon) {
            $this->worker->setCache($this->laravel['cache']->driver());
        }

        $queue = $this->getQueue($queue, $connection);
        $connection = $this->getConnectionName($connection);

        foreach (explode(',', $queue) as $tube) {
            $this->worker->eyeQueues[] = Queue::firstOrCreate(['connection' => $connection,
                                                               'tube' => $tube,
                                                               'driver' => $this->getDriverName($connection),
                                                               'healthy' => 1]);
        }

        return parent::runWorker($connection, $queue, $delay, $memory, $daemon);
    }

    /**
     * Get the default connection for this application.
     *
     * @param  string  $connection
     * @return string
     */
    protected function getConnectionName($connection)
    {
        return $connection ?: config('queue.default');
    }

    /**
     * Get the queue name for the worker.
     *
     * @param  string  $queue
     * @param  string  $connection
     * @return string
     */
    protected function getQueue($queue, $connection)
    {
        return $queue ?: config("queue.connections.{$connection}.queue", 'default');
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
