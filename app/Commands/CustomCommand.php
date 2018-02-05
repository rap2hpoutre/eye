<?php

namespace Eyewitness\Eye\Commands;

use Throwable;
use Eyewitness\Eye\Eye;
use Illuminate\Console\Command;

class CustomCommand extends Command
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
    protected $name = 'eyewitness:custom';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Eyewitness.io - to run custom witnesses as scheduled. Will be called automatically by the package.';

    /**
     * Indicates whether the command should be shown in the Artisan command list.
     *
     * @var bool
     */
    protected $hidden = true;

    /**
     * Create the command.
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
     * Execute the custom console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->info('Starting Custom Witness command...');

        foreach ($this->eye->getCustomWitnesses(true) as $witness) {
            $this->info('Running: '.$witness->getSafeName());

            $status = $this->runWitness($witness);
            $witness->saveHistory($status);
            $witness->checkHealth($status);
        }

        $this->info('Custom Witness command complete...');
    }

    /**
     * Run the custom witness.
     *
     * @param  \Eyewitness\Eye\Monitors\Custom  $witness
     * @return bool
     */
    protected function runWitness($witness)
    {
        try {
            $status = $witness->run();
            $status = ($status) ?: false;
        } catch (Throwable $t) {
            $status = false;
        }

        return $status;
    }
}
