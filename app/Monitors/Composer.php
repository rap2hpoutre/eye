<?php

namespace Eyewitness\Eye\Monitors;

use Eyewitness\Eye\Repo\History\Composer as History;
use Eyewitness\Eye\Notifications\Messages\Composer\Safe;
use Eyewitness\Eye\Notifications\Messages\Composer\Risk;

class Composer extends BaseMonitor
{
    /**
     * Poll the Composer for its checks.
     *
     * @return void
     */
    public function poll()
    {
        $latest = $this->eye->api()->composer();

        if (is_null($latest)) {
            return;
        }

        History::where('type', 'composer')->delete();

        History::create([
            'type' => 'composer',
            'meta' => 'composer',
            'record' => $latest
        ]);

        if ($latest === []) {
            if ($this->eye->status()->isSick('composer')) {
                $this->eye->notifier()->alert(new Safe);
            }

            $this->eye->status()->setHealthy('composer');
        } else {
            if ($this->eye->status()->isHealthy('composer')) {
                $this->eye->notifier()->alert(new Risk);
            }

            $this->eye->status()->setSick('composer');
        }
    }
}
