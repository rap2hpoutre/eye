<?php

namespace Eyewitness\Eye\App\Scheduling;

use Illuminate\Contracts\Container\Container;

trait BaseEventTrait
{
    /**
     * The eye instance.
     *
     * @var \Eyewitness\Eye\Eye;
     */
    protected $eye;

    /**
     * Force the command to run regardless of mutex.
     *
     * @var mixed
     */
    public $ignoreMutex = false;

    /**
     * Remember if the event wanted to run in the background.
     *
     * @var mixed
     */
    public $forceRunInForeground = false;

    /**
     * The start time of the job.
     *
     * @var float
     */
    public $start;

    /**
     * The id of the job.
     *
     * @var string
     */
    public $jobId;

    /**
     * The exit code of the job.
     *
     * @var string
     */
    public $exitcode;

    /**
     * Generate a job id for this event.
     *
     * @return string
     */
    public function generateJobId()
    {
        return time().'_'.str_random(20);
    }

    /**
     * Calculate run time for this event.
     *
     * @return float
     */
    public function calculateRunTime()
    {
        return round(microtime(true) - $this->start, 4);
    }

    /**
     * Get the summary of the event for display.
     *
     * @return string
     */
    public function getSummaryForDisplay()
    {
        return $this->expression.' : '.$this->getCommandName();
    }

    /**
     * Get the summary of the event for display.
     *
     * @return string
     */
    protected function getCommandName()
    {
        if ($this->command) {
            return $this->command;
        }

        if ($this->description) {
            return $this->description;
        }

        if (isset($this->callback) && is_string($this->callback)) {
            return $this->callback;
        }

        return 'Unnamed Closure';
    }

    /**
     * State that the command should be forced to run regardless of mutex.
     *
     * @return $this
     */
    public function ignoreMutex($force)
    {
        $this->ignoreMutex = $force;

        return $this;
    }

    /**
     * State that the command should run in the foreground.
     *
     * @return $this
     */
    public function runInForeground()
    {
        $this->forceRunInForeground = true;

        return $this;
    }

    /**
     * Check if output is being captured.
     *
     * @return bool
     */
    protected function outputNotBeingCaptured()
    {
        if (is_null($this->output)) {
            return true;
        }

        if (is_callable([$this, 'getDefaultOutput'])) {
            return $this->output === $this->getDefaultOutput();
        }

        return in_array($this->output, ['NUL', '/dev/null']);
    }

    /**
     * Set everything and send a starting ping of the job to Eyewitness.
     *
     * @return void
     */
    protected function sendStartPing()
    {
        $this->start = microtime(true);
        $this->jobId = $this->generateJobId();
        $this->exitcode = 1;

        $this->eye->api()->sendSchedulerPing([
            'job_id' => $this->jobId,
            'command' => $this->getCommandName(),
            'schedule' => $this->expression,
            'timezone' => $this->timezone,
            'background' => $this->runInBackground,
            'mutex' => $this->mutexName(),
        ]);
    }

    /**
     * Send a end ping and results of the of the job to Eyewitness.
     *
     * @return void
     */
    protected function sendEndPing()
    {
        $this->eye->api()->sendSchedulerPing([
            'job_id' => $this->jobId,
            'command' => $this->getCommandName(),
            'schedule' => $this->expression,
            'timezone' => $this->timezone,
            'background' => $this->runInBackground,
            'mutex' => $this->mutexName(),
            'time' => $this->calculateRunTime(),
            'exitcode' => $this->exitcode,
            'output' => $this->retrieveOutput(),
        ]);
    }

    /**
     * Run the command in the foreground.
     *
     * @param  \Illuminate\Contracts\Container\Container  $container
     * @return void
     */
    protected function runCommandInForeground(Container $container)
    {
        register_shutdown_function(function () {
            $this->mutex->forget($this);
        });

        $this->ensureOutputIsBeingCapturedForEyewitness();
        $this->sendStartPing();
        $this->callBeforeCallbacks($container);

        try {
            $this->runForegroundProcess();
        } finally {
            $this->callAfterCallbacks($container);
            $this->sendEndPing();
        }
    }
}
