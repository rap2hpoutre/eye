<?php

namespace Eyewitness\Eye\Commands;

use Illuminate\Console\Command;
use Eyewitness\Eye\Eye;

class DebugCommand extends Command
{
    /**
     * The eye instance.
     *
     * @var \Eyewitness\Eye\Eye;
     */
    protected $eye;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'eyewitness:debug';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Eyewitness.io - configuration information and debugging for support issues';

    /**
     * Create the Debug command.
     *
     * @param  \Eyewitness\Eye\Eye  $eye
     * @return void
     */
    public function __construct(Eye $eye)
    {
        parent::__construct();

        $this->eye = $eye;
    }

    /**
     * Execute the debug console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->whoAmI();
        $this->testConnection();
    }

    /**
     * Show the current Eyewitness.io credentials being used. This is useful to help some users
     * forget to reload their config cache.
     *
     * @return void
     */
    protected function whoAmI()
    {
        $this->info('______________________________________');
        $this->info(' ');
        $this->info('According to the currently loaded configuration on this server - your Eyewitness.io details are:');
        $this->info(' ');
        $this->info('   App Token: '.config('eyewitness.app_token'));
        $this->info('   Secret Key: '.config('eyewitness.secret_key'));
        $this->info(' ');
    }

    /**
     * Try and connection to the Eyewitness.io server using the current credentials.
     *
     * @return void
     */
    protected function testConnection()
    {
        $this->info('______________________________________');
        $this->info(' ');
        $this->info('Trying to connection to Eyewitness.io server...');
        $this->info(' ');

        $results = $this->eye->api()->sendTestConnectionPing();

        if ($results[0] == "200") {
            $this->info("Status Code: ".$results[0]);
            $this->info("Status Message: ".$results[1]);
        } else {
            $this->error("Status Code: ".$results[0]);
            $this->error("Status Message: ".$results[1]);
        }
    }
}
