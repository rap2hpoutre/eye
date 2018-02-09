<?php

namespace Eyewitness\Eye\Scheduling;

use Eyewitness\Eye\Eye;
use Illuminate\Console\Application;
use Illuminate\Support\ProcessUtils as LaravelProcessUtils;
use Symfony\Component\Process\ProcessUtils as SymfonyProcessUtils;
use Illuminate\Contracts\Container\Container;
use Symfony\Component\Process\PhpExecutableFinder;

trait ApplicationBackport
{
    /**
     * Finalize the event's command syntax with the correct user.
     *
     * @param  string  $command
     * @return string
     */
    protected function formatCommandString($string)
    {
        if (Eye::laravelVersionIs('>=', '5.4.0')) {
            return Application::formatCommandString($string);
        }

        return sprintf('%s %s %s', $this->phpBinary(), $this->artisanBinary(), $string);
    }

    /**
     * Determine the proper PHP executable.
     *
     * https://github.com/laravel/framework/pull/22448
     *
     * @return string
     */
    public static function phpBinary()
    {
        if (Eye::laravelVersionIs('<', '5.5.26')) {
            return SymfonyProcessUtils::escapeArgument((new PhpExecutableFinder)->find(false));
        }

        return LaravelProcessUtils::escapeArgument((new PhpExecutableFinder)->find(false));
    }

    /**
     * Determine the proper Artisan executable.
     *
     * @return string
     */
    public static function artisanBinary()
    {
        if (Eye::laravelVersionIs('<', '5.5.26')) {
            return defined('ARTISAN_BINARY') ? SymfonyProcessUtils::escapeArgument(ARTISAN_BINARY) : 'artisan';
        }

        return defined('ARTISAN_BINARY') ? LaravelProcessUtils::escapeArgument(ARTISAN_BINARY) : 'artisan';
    }
}
