<?php

namespace Eyewitness\Eye\Commands;

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
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Eyewitness.io poll command';

    /**
     * Create the Server command.
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
        $this->eye->api()->sendServerPing($this->eye->runAllChecks());

        $this->info('Eyewitness.io server poll command complete.');
    }
}
