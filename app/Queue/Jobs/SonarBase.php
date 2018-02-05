<?php

namespace Eyewitness\Eye\Queue\Jobs;

use Eyewitness\Eye\Repo\Queue;
use Illuminate\Support\Facades\Cache;
use Illuminate\Queue\InteractsWithQueue;

class SonarBase
{
    /**
     * Store the created time.
     *
     * @var float
     */
    public $created;


    /**
     * Store the queue_id we are tracking for.
     *
     * @var string
     */
    public $queue_id;

    /**
     * Store the tube the job was pushed on.
     *
     * @var string
     */
    public $tube;

    /**
     * Store the connection the job was pushed on.
     *
     * @var string
     */
    public $connection;

    /**
     * Create a new job instance.
     *
     * @param  string  $queue_id
     * @param  string  $connection
     * @param  string  $tube
     * @return void
     */
    public function __construct($queue_id, $connection, $tube)
    {
        $this->queue_id = $queue_id;
        $this->tube = $tube;
        $this->connection = $connection;
        $this->created = microtime(true);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $end_time = round((microtime(true) - $this->created)*1000);

        Cache::forget('eyewitness_q_sonar_deployed_'.$this->queue_id);
        Cache::put('eyewitness_q_current_wait_time_'.$this->queue_id, $end_time, 180);
        Cache::add('eyewitness_q_sonar_time_'.$this->queue_id, 0, 180);
        Cache::increment('eyewitness_q_sonar_time_'.$this->queue_id, $end_time);
        Cache::add('eyewitness_q_sonar_count_'.$this->queue_id, 0, 180);
        Cache::increment('eyewitness_q_sonar_count_'.$this->queue_id);

        $queue = Queue::find($this->queue_id);
        $queue->current_wait_time = round($end_time/1000, 2);
        $queue->save();
    }
}
