<?php

namespace Eyewitness\Eye\Queue;

use Illuminate\Database\QueryException;

class DatabaseQueue extends Handler
{
    /**
     * The queue table name.
     *
     * @var string
     */
    protected $table;

    /**
     * Create a new Database queue instance.
     *
     * @param string  $connection
     * @param string  $connection
     * @return void
     */
    public function __construct($connection, $config)
    {
        $this->queue = $connection->getDatabase();

        $this->table = $config['table'];
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
            $count = $this->queue->table($this->table)
                                 ->whereNull('reserved_at')
                                 ->where('queue', $tube)
                                 ->where('available_at', '<=', time())
                                 ->count();
        } catch (QueryException $e) {
            $count = 0;
        }

        return $count;
    }
}
