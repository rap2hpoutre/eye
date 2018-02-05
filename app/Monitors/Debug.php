<?php

namespace Eyewitness\Eye\Monitors;

use Eyewitness\Eye\Notifications\Messages\Debug\Enabled;
use Eyewitness\Eye\Notifications\Messages\Debug\Disabled;

class Debug extends BaseMonitor
{
    /**
     * Poll the Debug for its checks.
     *
     * @return void
     */
    public function poll()
    {
        if (! app()->environment('production')) {
            $this->eye->status()->setHealthy('debug');
            return;
        }

        if (config('app.debug')) {
            if ($this->eye->status()->isHealthy('debug')) {
                $this->eye->notifier()->alert(new Enabled);
            }

            $this->eye->status()->setSick('debug');
        } else {
            if ($this->eye->status()->isSick('debug')) {
                $this->eye->notifier()->alert(new Disabled);
            }

            $this->eye->status()->setHealthy('debug');
        }
    }
}
