<?php

namespace Eyewitness\Eye\Commands;

use Illuminate\Console\Scheduling\ScheduleRunCommand as OriginalScheduleRunCommand;
use Eyewitness\Eye\Eye;

class ScheduleRunCommand extends OriginalScheduleRunCommand
{
    /**
     * Execute the console command. This is an extension of the original run command
     * but we just insert our timing and ping calls throughout.
     *
     * @return void
     */
    public function fire()
    {
        $eventsRan = false;
        $eventResults = [];

        foreach ($this->schedule->dueEvents($this->laravel) as $event) {
            if ($this->canAccessFiltersPass($event) && (! $event->filtersPass($this->laravel))) {
                continue;
            }

            $this->line('<info>Running scheduled command:</info> '.$event->getSummaryForDisplay());

            // Run the command
            $start_time = microtime(true);
            $event->run($this->laravel);
            $end_time = microtime(true);

            // Capture the command and how long it took to run
            $eventResults[] = ['command' => $event->command,
                               'schedule' => $event->expression,
                               'timezone' => $event->timezone,
                               'time' => round($end_time - $start_time, 4)];

            $eventsRan = true;
        }

        $eye = app(Eye::class);

        if ($eventsRan) {
            // Ping the Eyewitness server with the scheduler result
            $eye->api()->sendSchedulerPing($eventResults);
        } else {
            // Check if we have pinged Eyewitness recently
            if (! $this->laravel['cache']->driver()->has('eyewitness_scheduler_heartbeat')) {
                // We need to ping Eyewitness, even though no events ran. This just confirms
                // that the scheduler itself is working, just nothing to process
                $eye->api()->sendSchedulerPing();
            }
            $this->info('No scheduled commands are ready to run.');
        }

        // Cache the heartbeat for 6 mins - so we only ping when required, not every cycle.
        $this->laravel['cache']->driver()->add('eyewitness_scheduler_heartbeat', 1, 6);
    }

    /**
     * Allow for simulatenous support of Laravel 5.5 and <=5.4 which is due to changes
     * in PR https://github.com/laravel/framework/pull/19827
     *
     * @return void
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
