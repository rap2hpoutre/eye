<?php

namespace Eyewitness\Eye\App\Scheduling;

use Illuminate\Console\Scheduling\Event as OriginalCallbackEvent;
use Eyewitness\Eye\App\Scheduling\BaseEventTrait;
use Illuminate\Contracts\Container\Container;
use Eyewitness\Eye\Eye;

class CallbackEvent extends OriginalCallbackEvent
{
    use BaseEventTrait;

    /**
     * Create a new custom child event instance.
     * https://stackoverflow.com/a/4722587/1317935
     *
     * @param  \Illuminate\Console\Scheduling\Event  $object
     * @return void
     */
    public function __construct(OriginalEvent $object)
    {
        foreach($object as $property => $value) {
            $this->$property = $value;
        }

        $this->runInBackground = false;

        $this->eye = app(Eye::class);
    }

    /**
     * Run the given event.
     *
     * @param  \Illuminate\Contracts\Container\Container  $container
     * @return mixed
     *
     * @throws \Exception
     */
    public function run(Container $container)
    {
        if ((! $this->ignoreMutex) && $this->description && $this->withoutOverlapping && (! $this->mutex->create($this))) {
            return;
        }

        $this->runCommandInForeground($container);

        return $this->exitcode;
    }

    /**
     * Run the foreground process.
     *
     * @return void
     */
    protected function runForegroundProcess()
    {
       $this->exitcode = $container->call($this->callback, $this->parameters);
    }

    /**
     * Ensure that output is being captured if not already set by the event.
     *
     * @return void
     */
    protected function ensureOutputIsBeingCapturedForEyewitness()
    {
        if (! config('eyewitness.capture_cron_output')) {
            return;
        }

        ob_start();
    }

    /**
     * Get the output from the last scheduled job. Only remove the file if we created
     * it specifically for Eyewitness - otherwise leave it alone for the framework
     * to handle as normal.
     *
     * @return mixed
     */
    protected function retrieveOutput()
    {
        if (! config('eyewitness.capture_cron_output')) {
            return null;
        }

        $output = $this->storeOutput(ob_get_contents());
        ob_end_flush();

        return $output;
    }
}
