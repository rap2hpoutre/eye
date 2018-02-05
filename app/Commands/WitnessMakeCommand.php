<?php

namespace Eyewitness\Eye\Commands;

use Illuminate\Console\GeneratorCommand;

class WitnessMakeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:witness';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Eyewitness custom Witness class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Witness';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/../../resources/stubs/witness.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\\'.config('eyewitness.custom_witness_namespace', 'Eyewitness');
    }
}
