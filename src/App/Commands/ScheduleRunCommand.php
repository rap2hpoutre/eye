<?php

namespace Eyewitness\Eye\App\Commands;

use Illuminate\Console\Scheduling\ScheduleRunCommand as OriginalScheduleRunCommand;
use Eyewitness\Eye\App\Scheduling\ConvertsEvent;
use Eyewitness\Eye\Eye;

class ScheduleRunCommand extends OriginalScheduleRunCommand
{
    use ConvertsEvent;

    /**
     * Execute the console command. This is an extension of the original run command
     * but allows us to insert our custom event and tracking.
     *
     * @return void
     */
    public function fire()
    {
        $eventsRan = false;

        foreach ($this->schedule->dueEvents($this->laravel) as $event) {
            $event = $this->convertEvent($event);

            if ($this->canAccessFiltersPass($event) && (! $event->filtersPass($this->laravel))) {
                continue;
            }

            $this->line('<info>Running scheduled command:</info> '.$event->getSummaryForDisplay());

            $event->run($this->laravel);

            $eventsRan = true;
        }

        if (! $eventsRan) {
            $this->info('No scheduled commands are ready to run.');
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
