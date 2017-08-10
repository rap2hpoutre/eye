<?php

namespace Eyewitness\Eye\App\Commands;

use Eyewitness\Eye\App\Scheduling\ConvertsEvent;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Console\Command;

class BackgroundRunCommand extends Command
{
    use ConvertsEvent;

    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'eyewitness:background {id} {--force}';

    /**
     * The console command name (duplicated for Laravel 5.0).
     *
     * @var string
     */
    protected $name;

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

        $this->name = $this->signature;

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        if (! $event = $this->findEventMutex()) {
            $this->error('No scheduled event could be found that matches the given id.');
            return;
        }

        $this->convertEvent($event)
             ->ignoreMutex($this->option('force'))
             ->runInForeground()
             ->run($this->laravel);
    }

    /**
     * Find the event that matches the mutex
     *
     * @return mixd
     */
    protected function findEventMutex()
    {
        return collect($this->schedule->events())->filter(function ($value) {
            if (laravel_version_is('<', '5.4.0')) {
                $value = $this->convertEvent($value);
            }

            return $value->mutexName() === $this->argument('id');
        })->first();
    }
}
