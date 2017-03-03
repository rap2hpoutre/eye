<?php

namespace Eyewitness\Eye\Commands;

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
        config(['eyewitness.temp_connection_name' => $connection]);

        return parent::runWorker($connection, $queue);
    }
}
