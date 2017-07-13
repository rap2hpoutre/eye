<?php

namespace Eyewitness\Eye\Commands;

use Illuminate\Console\Command;
use Eyewitness\Eye\Eye;

class TestConnectionCommand extends Command
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
    protected $name = 'eyewitness:test-connection';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Eyewitness.io test connection to server command for debugging';

    /**
     * Create the Test Connection command.
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
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
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
