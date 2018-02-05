<?php

namespace Eyewitness\Eye\Commands;

use Eyewitness\Eye\Eye;
use Illuminate\Console\Command;

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
        $this->testPing();
        $this->testAuthenticate();
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
        $this->table(
          ['Eyewitness', 'Value'],
          [
            ['App Token', config('eyewitness.app_token')],
            ['Secret Key', config('eyewitness.secret_key')]
          ]
        );
        $this->info(' ');
    }

    /**
     * Try and ping the Eyewitness.io server.
     *
     * @return void
     */
    protected function testPing()
    {
        $this->info('______________________________________');
        $this->info(' ');
        $this->info('Trying to ping Eyewitness.io server...');
        $this->info(' ');

        $results = $this->eye->api()->sendTestPing();

        if ($results['pass']) {
            $this->info("Status Code: ".$results['code']);
            $this->info("Status Message: ".$results['message']);
        } else {
            $this->info("Status Code: ".$results['code']);
            $this->info("Status Message: ".$results['message']);
        }
    }

    /**
     * Try and authenticate to the Eyewitness.io server using the current credentials.
     *
     * @return void
     */
    protected function testAuthenticate()
    {
        $this->info('______________________________________');
        $this->info(' ');
        $this->info('Trying to authenticate to Eyewitness.io server...');
        $this->info(' ');

        $results = $this->eye->api()->sendTestAuthenticate();

        if ($results['pass']) {
            $this->info("Status Code: ".$results['code']);
            $this->info("Status Message: ".$results['message']);
        } else {
            $this->info("Status Code: ".$results['code']);
            $this->info("Status Message: ".$results['message']);
        }
    }
}
