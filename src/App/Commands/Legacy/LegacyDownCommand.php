<?php

namespace Eyewitness\Eye\App\Commands\Legacy;

use Illuminate\Foundation\Console\DownCommand as OriginalDownCommand;
use Eyewitness\Eye\Eye;

class LegacyDownCommand extends OriginalDownCommand
{
    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        parent::fire();

        app(Eye::class)->api()->down();
    }
}
