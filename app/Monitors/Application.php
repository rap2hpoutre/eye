<?php

namespace Eyewitness\Eye\Monitors;

class Application extends BaseMonitor
{
    /**
     * The checked settings.
     *
     * @var array
     */
    protected $settings;

    /**
     * Get all the application settings.
     *
     * @return array
     */
    public function settings()
    {
        if (is_null($this->settings)) {
            $this->settings['version_php'] = $this->getPhpVersion();
            $this->settings['version_laravel'] = app()->version();
            $this->settings['cache_config'] = app()->configurationIsCached();
            $this->settings['cache_route'] = app()->routesAreCached();
            $this->settings['timezone'] = config('app.timezone');
            $this->settings['debug'] = config('app.debug');
            $this->settings['env'] = config('app.env');
            $this->settings['name'] = config('app.name', 'Laravel App');
            $this->settings['maintenance_mode'] = app()->isDownForMaintenance();
        }

        return $this->settings;
    }

    /**
     * Get a specifc application result.
     *
     * @param  string  $filter
     * @return array
     */
    public function find($filter)
    {
        if (is_null($this->settings)) {
            $this->settings();
        }

        return $this->settings[$filter];
    }

    /**
     * Get a clean version of the PHP version.
     *
     * @return string
     */
    protected function getPhpVersion()
    {
        $php = explode('-', phpversion());

        return $php[0];
    }
}
