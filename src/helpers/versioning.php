<?php

/**
 * Compare Laravel 5.x version to the variable given
 *
 * @param  double  $version
 * @return boolean
 */
if (! function_exists('laravel_version_is')) {
    function laravel_version_is($operator, $version, $laravel = null)
    {
        if (is_null($laravel)) {
            $laravel = app()->version();
        }

        return version_compare($laravel, $version, $operator);
    }
}
