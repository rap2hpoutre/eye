<?php

namespace Eyewitness\Eye\Queue\Handlers;

use Pheanstalk\Exception\ServerException;

class BeanstalkdQueue extends BaseHandler
{
    /**
     * Create a new Beanstalkd queue instance.
     *
     * @param  \Illuminate\Contracts\Queue\Queue  $connection
     * @param  \Eyewitness\Eye\Repo\Queue  $queue
     * @return void
     */
    public function __construct($connection, $queue)
    {
        $this->queue = $connection->getPheanstalk();
    }

    /**
     * Return the number of pending jobs for the tube.
     *
     * @param  string  $tube
     * @return int
     */
    public function pendingJobsCount($tube)
    {
        try {
            $count = $this->queue->statsTube($tube)->{'current-jobs-ready'};
        } catch (ServerException $e) {
            $count = 0;
        }

        return $count;
    }
}
