<?php

use Illuminate\Support\Facades\Route;

Route::group([
        'prefix' => config('eyewitness.base_uri', 'eyewitness'),
        'middleware' => [],
        'namespace' => 'Eyewitness\Eye\Http\Controllers'
], function () {
    Route::get('asset/{cache_buster}/eyewitness.css',      ['as' => 'eyewitness.asset.css',        'uses' => 'AssetController@css']);
    Route::get('asset/{cache_buster}/eyewitness.js',       ['as' => 'eyewitness.asset.js',         'uses' => 'AssetController@js']);
});
