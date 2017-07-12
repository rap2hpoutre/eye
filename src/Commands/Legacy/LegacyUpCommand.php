<?php

namespace Eyewitness\Eye\Commands\Legacy;

use Illuminate\Foundation\Console\UpCommand as OriginalUpCommand;
use Eyewitness\Eye\Eye;

class LegacyUpCommand extends OriginalUpCommand
{
    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        parent::fire();

        app(Eye::class)->api()->up();
    }
}
