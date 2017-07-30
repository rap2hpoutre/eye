<?php

namespace Eyewitness\Eye\App\Commands;

use Illuminate\Foundation\Console\UpCommand as OriginalUpCommand;
use Eyewitness\Eye\Eye;

class UpCommand extends OriginalUpCommand
{
    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        parent::handle();

        app(Eye::class)->api()->up();
    }
}
