<?php

namespace Eyewitness\Eye\App\Commands;

use Illuminate\Queue\Console\WorkCommand as OriginalWorkCommand;

class WorkCommand extends OriginalWorkCommand
{
    /**
     * We add a small way to store the current connection *name* in use,
     * to be able to access it later in the queue process.
     *
     * @param  string  $connection
     * @param  string  $queue
     * @return array
     */
    protected function runWorker($connection, $queue)
    {
        $this->worker->eyeConnection = $connection;
        $this->worker->eyeQueues = explode(',', $queue);

        return parent::runWorker($connection, $queue);
    }
}
