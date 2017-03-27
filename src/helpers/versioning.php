<?php

/**
 * Get the Laravel 5.x version number
 *
 * @return double
 */
if (! function_exists('get_laravel_version')){
    function get_laravel_version()
    {
        $parts = explode('.', app()->version());
        return (double) ($parts[0].'.'.$parts[1]);
    }
}

/**
 * Determine if the current Laravel 5.x version is greater than
 * the variable given
 *
 * @param  double  $version
 * @return boolean
 */
if (! function_exists('laravel_version_greater_than_or_equal_to')) {
    function laravel_version_greater_than_or_equal_to($version)
    {
        return (get_laravel_version() >= $version);
    }
}

/**
 * Determine if the current Laravel 5.x version is less than
 * the variable given
 *
 * @param  double  $version
 * @return boolean
 */
if (! function_exists('laravel_version_less_than_or_equal_to')) {
    function laravel_version_less_than_or_equal_to($version)
    {
        return (get_laravel_version() <= $version);
    }
}

/**
 * Determine if the current Laravel 5.x version is equal to
 * the variable given
 *
 * @param  double  $version
 * @return boolean
 */
if (! function_exists('laravel_version_equal_to')) {
    function laravel_version_equal_to($version)
    {
        return (get_laravel_version() == $version);
    }
}
