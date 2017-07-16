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
        $eventResults = [];

        foreach ($this->schedule->dueEvents($this->laravel) as $event) {
            if ($this->canAccessFiltersPass($event) && (! $event->filtersPass($this->laravel))) {
                continue;
            }

            $eventResults[] = $this->runScheduledEvent($event);
        }

        if (count($eventResults)) {
            app(Eye::class)->api()->sendSchedulerPing($eventResults);
        } else {
            $this->sendSchedulerHeartBeat();
            $this->info('No scheduled commands are ready to run.');
        }

        // Cache the heartbeat for 6 mins - so we only ping when required, not every cycle.
        $this->laravel['cache']->driver()->add('eyewitness_scheduler_heartbeat', 1, 6);
    }

    /**
     * Run the scheduled event.
     *
     * @param  $event
     * @return array
     */
    protected function runScheduledEvent($event)
    {
        if (config('eyewitness.capture_cron_output')) {
            $event = $this->ensureOutputIsBeingCapturedForEyewitness($event);
        }

        $this->line('<info>Running scheduled command:</info> '.$event->getSummaryForDisplay());

        // Run the command
        $start_time = microtime(true);
        $event->run($this->laravel);
        $end_time = microtime(true);

        // Capture the command and how long it took to run
        return ['command' => $this->getCommandName($event),
                'schedule' => $event->expression,
                'timezone' => $event->timezone,
                'time' => round($end_time - $start_time, 4),
                'output' => $this->captureOutput($event)];
    }

    /**
     * Ensure that output is being captured for Eyewitness.
     *
     * @param  $event
     * @return mixed
     */
    protected function ensureOutputIsBeingCapturedForEyewitness($event)
    {
        if (is_null($event->output) || $event->output == $event->getDefaultOutput()) {
            $event->output = storage_path('eyewitness_cron_output_'.sha1($event->expression.$event->command).'.cron.log');
        }

        return $event;
    }

    /**
     * Get the output from the last scheduled job. Only remove the file if we created
     * it specifically for Eyewitness - otherwise leave it alone for the framework
     * to handle as normal.
     *
     * @param  $event
     * @return mixed
     */
    protected function captureOutput($event)
    {
        if ((! config('eyewitness.capture_cron_output')) || (! file_exists($event->output))) {
            return null;
        }

        $text = file_get_contents($event->output);

        if (str_contains($event->output, 'eyewitness_cron_output_')) {
            unlink($event->output);
        }

        return $text;
    }

    /**
     * Determine if we need to ping Eyewitness, even though no events ran. This just confirms
     * that the scheduler itself is working, just nothing to process.
     *
     * @return void
     */
    protected function sendSchedulerHeartBeat()
    {
        if (! $this->laravel['cache']->driver()->has('eyewitness_scheduler_heartbeat')) {
            app(Eye::class)->api()->sendSchedulerPing();
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

    /**
     * If the schedule command is a closure, we need to use the description if available,
     * using the same outline as a command.
     *
     * @param  $event
     * @return string
     */
    protected function getCommandName($event)
    {
        if (is_null($event->command)) {
            return 'php artisan '.$event->description;
        }

        return $event->command;
    }
}
