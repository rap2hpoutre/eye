<?php

use Eyewitness\Eye\Eye;
use Illuminate\Support\Facades\Route;

Route::group([
        'prefix' => config('eyewitness.base_uri', 'eyewitness'),
        'middleware' => (Eye::laravelVersionIs('>=', '5.2.0') ? [config('eyewitness.route_middleware')] : []),
        'namespace' => 'Eyewitness\Eye\Http\Controllers'
], function () {
    Route::get('/',         ['as' => 'eyewitness.login',             'uses' => 'AuthController@login']);
    Route::post('/',        ['as' => 'eyewitness.authenticate',      'uses' => 'AuthController@authenticate']);
    Route::post('logout',   ['as' => 'eyewitness.logout',            'uses' => 'AuthController@logout']);
});
