<?php

namespace Eyewitness\Eye\Queue\Handlers;

class RedisQueue extends BaseHandler
{
    /**
     * Create a new Redis queue instance.
     *
     * @param  \Illuminate\Contracts\Queue\Queue  $connection
     * @param  \Eyewitness\Eye\Repo\Queue  $queue
     * @return void
     */
    public function __construct($connection, $queue)
    {
        $this->queue = $connection->getRedis();
    }

    /**
     * Return the number of pending jobs for the tube.
     *
     * @param  string  $tube
     * @return int
     */
    public function pendingJobsCount($tube)
    {
        return $this->queue->llen('queues:'.$tube);
    }
}
