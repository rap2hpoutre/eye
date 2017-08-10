<?php

namespace Eyewitness\Eye\App\Scheduling;

use Symfony\Component\Process\PhpExecutableFinder;
use Illuminate\Contracts\Container\Container;
use Symfony\Component\Process\ProcessUtils;
use Illuminate\Console\Application;

trait ApplicationBackportTrait
{
    /**
     * Finalize the event's command syntax with the correct user.
     *
     * @param  string  $command
     * @return string
     */
    protected function formatCommandString($string)
    {
        if (laravel_version_is('>=', '5.4.0')) {
            return Application::formatCommandString($string);
        }

        return sprintf('%s %s %s', $this->phpBinary(), $this->artisanBinary(), $string);
    }

    /**
     * Determine the proper PHP executable.
     *
     * @return string
     */
    public static function phpBinary()
    {
        return ProcessUtils::escapeArgument((new PhpExecutableFinder)->find(false));
    }

    /**
     * Determine the proper Artisan executable.
     *
     * @return string
     */
    public static function artisanBinary()
    {
        return defined('ARTISAN_BINARY') ? ProcessUtils::escapeArgument(ARTISAN_BINARY) : 'artisan';
    }
}
