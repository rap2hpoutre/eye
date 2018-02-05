<?php

use Illuminate\Support\Facades\Route;

Route::group([
        'prefix' => config('eyewitness.base_uri', 'eyewitness').'/v3',
        'middleware' => ['eyewitness_auth'],
        'namespace' => 'Eyewitness\Eye\Http\Controllers\Api'
], function () {
    //
});
