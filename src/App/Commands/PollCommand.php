<?php

namespace Eyewitness\Eye\App\Commands;

use Illuminate\Console\Command;
use Eyewitness\Eye\Eye;

class PollCommand extends Command
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
    protected $name = 'eyewitness:poll';

    /**
     * Indicates whether the command should be shown in the Artisan command list.
     *
     * @var bool
     */
    protected $hidden = true;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Eyewitness.io - command to poll the server. Will be called automatically.';

    /**
     * Create the Poll command.
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
        $this->eye->api()->sendServerPing($this->eye->runAllChecks(), true);

        $this->info('Eyewitness.io server poll command complete.');
    }
}
