<?php

namespace Eyewitness\Eye\App\Commands;

use Illuminate\Console\Scheduling\ScheduleRunCommand as OriginalScheduleRunCommand;
use Eyewitness\Eye\App\Scheduling\CustomEvents;
use Eyewitness\Eye\Eye;

class ScheduleRunCommand extends OriginalScheduleRunCommand
{
    use CustomEvents;

    /**
     * Determine if any events ran.
     *
     * @var bool
     */
    protected $eventsRan = false;

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $this->runScheduledEvents();

        $this->runAdhocEvents();

        if (! $this->eventsRan) {
            $this->info('No scheduled commands are ready to run.');
        }
    }

    /**
     * Run the scheduled events. This is an extension of the original run command
     * but allows us to insert our custom event and tracking.
     *
     * @return void
     */
    protected function runScheduledEvents()
    {
        foreach ($this->schedule->dueEvents($this->laravel) as $event) {
            $event = $this->convertEvent($event);

            if ($this->canAccessFiltersPass($event) && (! $event->filtersPass($this->laravel))) {
                continue;
            }

            $this->line('<info>Running scheduled command:</info> '.$event->getSummaryForDisplay());

            $event->run($this->laravel);

            $this->eventsRan = true;
        }
    }

    /**
     * Run any adhoc event requests we received via the API.
     *
     * @return void
     */
    protected function runAdhocEvents()
    {
        if (app()->make('cache')->has('eyewitness_scheduler_adhoc')) {
            foreach(json_decode(app()->make('cache')->pull('eyewitness_scheduler_adhoc'), true) as $mutex) {
                if (! $event = $this->findEventMutex($mutex)) {
                    continue;
                }

                $this->line('<info>Running adhoc command: </info> '.$event->getSummaryForDisplay());

                $this->runEvent($event);
            }

            $this->eventsRan = true;
        }
    }

    /**
     * Allow for simulatenous support of Laravel 5.5 and <=5.4 which is due to changes
     * in PR https://github.com/laravel/framework/pull/19827.
     *
     * @return mixed
     */
    public function handle()
    {
        return $this->fire();
    }

    /**
     * Due to PR https://github.com/laravel/framework/pull/11646/ we should check
     * if the filtersPass() method can be called or not.
     *
     * @param  $event
     * @return bool
     */
    protected function canAccessFiltersPass($event)
    {
        return is_callable([$event, 'filtersPass']);
    }
}
