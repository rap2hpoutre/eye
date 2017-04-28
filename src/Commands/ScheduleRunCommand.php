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
            // We need to check what version we are running, as Laravel <=5.1 does not have the filtersPass()
            // function to check against
            if (laravel_version_greater_than_or_equal_to(5.2) && (! $event->filtersPass($this->laravel))) {
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
}
