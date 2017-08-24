<?php

namespace Eyewitness\Eye\App\Jobs;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;

class SonarBase
{
    /**
     * Store the created time.
     *
     * @var float
     */
    public $created;

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
     * @return void
     */
    public function __construct($connection, $tube)
    {
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

        Cache::forget('eyewitness_q_sonar_deployed_'.$this->connection.'_'.$this->tube);
        Cache::put('eyewitness_q_current_wait_time_'.$this->connection.'_'.$this->tube, $end_time, 180);
        Cache::add('eyewitness_q_wait_time_'.$this->connection.'_'.$this->tube, 0, 180);
        Cache::increment('eyewitness_q_wait_time_'.$this->connection.'_'.$this->tube, $end_time);
        Cache::add('eyewitness_q_wait_count_'.$this->connection.'_'.$this->tube, 0, 180);
        Cache::increment('eyewitness_q_wait_count_'.$this->connection.'_'.$this->tube);
    }
}
