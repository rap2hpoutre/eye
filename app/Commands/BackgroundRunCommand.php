<?php

namespace Eyewitness\Eye\Commands;

use Illuminate\Console\Command;
use Eyewitness\Eye\Scheduling\CustomEvents;
use Illuminate\Console\Scheduling\Schedule;

class BackgroundRunCommand extends Command
{
    use CustomEvents;

    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'eyewitness:background {id} {--force}';

    /**
     * Indicates whether the command should be shown in the Artisan command list.
     *
     * @var bool
     */
    protected $hidden = true;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Eyewitness.io - command to run background scheduled events. Will be called automatically.';

    /**
     * The schedule instance.
     *
     * @var \Illuminate\Console\Scheduling\Schedule
     */
    protected $schedule;

    /**
     * Create a new background run command instance.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    public function __construct(Schedule $schedule)
    {
        $this->schedule = $schedule;

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        if (! $event = $this->findEventMutex($this->argument('id'))) {
            $this->error('No scheduled event could be found that matches the given id.');
            return;
        }

        $this->runEvent($event, $this->option('force'));
    }
}
