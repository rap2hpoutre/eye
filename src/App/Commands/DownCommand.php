<?php

namespace Eyewitness\Eye\App\Commands;

use Illuminate\Foundation\Console\DownCommand as OriginalDownCommand;
use Eyewitness\Eye\Eye;

class DownCommand extends OriginalDownCommand
{
    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        parent::handle();

        app(Eye::class)->api()->down();
    }
}
