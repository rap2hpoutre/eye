<?php

namespace Eyewitness\Eye\App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Bus\SelfHandling;

class SonarLegacy extends SonarBase implements ShouldQueue, SelfHandling {}
