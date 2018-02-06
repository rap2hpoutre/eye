<?php

namespace Eyewitness\Eye\Scheduling;

use Carbon\Carbon;
use Eyewitness\Cron\CronExpression;

trait RunDue
{
    /**
     * Determine when the next occurance of the scheduler is due to run.
     *
     * @param  string  $schedule
     * @param  string  $timezone
     * @return  \Carbon\Carbon
     */
    public function getNextRunDue($schedule, $timezone)
    {
        return Carbon::instance(CronExpression::factory($schedule)->getNextRunDate('now', 0, false, $timezone));
    }

    /**
     * Determine when the next check of the scheduler is due.
     *
     * @param  string  $schedule
     * @param  string  $timezone
     * @param  int  $extra_seconds
     * @return  \Carbon\Carbon
     */
    public function getNextCheckDue($schedule, $timezone, $extra_seconds = 0)
    {
        return $this->getNextRunDue($schedule, $timezone)->addMinutes(5)->addSeconds($extra_seconds);
    }

    /**
     * Set the next checks for the scheduler.
     *
     * @param  \Eyewitness\Eye\Repo\Scheduler  $scheduler
     * @return void
     */
    public function setNextSchedule($scheduler)
    {
        $scheduler->next_run_due = $this->getNextRunDue($scheduler->schedule, $scheduler->timezone);
        $scheduler->next_check_due = $this->getNextCheckDue($scheduler->schedule, $scheduler->timezone, $scheduler->alert_run_time_greater_than);
        $scheduler->save();
    }

    /**
     * Determine when the scheduler is due to finish.
     *
     * @param  \Eyewitness\Eye\Repo\Scheduler  $scheduler
     * @return void
     */
    public function determineExpectedCompletion($scheduler)
    {
        // add 15mins (900) plus the alert time
        return date('Y-m-d H:i:s', time() + config('eyewitness.override_cron_default_time', 900) + $scheduler->alert_run_time_greater_than);
    }
}
