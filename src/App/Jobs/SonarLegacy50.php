<?php

namespace Eyewitness\Eye\App\Jobs;

use Illuminate\Contracts\Queue\ShouldBeQueued;
use Illuminate\Contracts\Bus\SelfHandling;

class SonarLegacy50 extends SonarBase implements ShouldBeQueued, SelfHandling {}
