<?php

namespace Eyewitness\Eye\Commands;

use Illuminate\Console\Command;

class WhoAmICommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'eyewitness:who-am-i';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Eyewitness.io debugging command to determine what config is loaded';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('______________________________________');
        $this->info(' ');
        $this->info('According to the currently loaded configuration on this server - your Eyewitness.io details are:');
        $this->info(' ');
        $this->info('   App Token: '.config('eyewitness.app_token'));
        $this->info('   Secret Key: '.config('eyewitness.secret_key'));
        $this->info(' ');
    }
}
