<?php

namespace Eyewitness\Eye\App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;
use Illuminate\Bus\Queueable;

class Sonar implements ShouldQueue, SelfHandling
{
    use InteractsWithQueue, Queueable;

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
        Cache::pull('eyewitness_q_sonar_deployed_'.$this->connection.'_'.$this->tube);
        Cache::add('eyewitness_q_wait_time_'.$this->connection.'_'.$this->tube.'_'.$tag, 0, 180);
        Cache::increment('eyewitness_q_wait_time_'.$this->connection.'_'.$this->tube.'_'.$tag, $end_time);
        Cache::add('eyewitness_q_wait_count_'.$this->connection.'_'.$this->tube.'_'.$tag, 0, 180);
        Cache::increment('eyewitness_q_wait_count_'.$this->connection.'_'.$this->tube.'_'.$tag);
    }
}
