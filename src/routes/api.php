<?php

Route::group(['prefix' => 'eyewitness_api/v1', 'middleware' => 'eyewitness_auth'], function () {
    Route::get('failed_queue',                  'Eyewitness\Eye\App\Http\Controllers\FailedQueueController@index');
    Route::get('failed_queue/delete/all',       'Eyewitness\Eye\App\Http\Controllers\FailedQueueController@delete_all');
    Route::get('failed_queue/delete/{id}',      'Eyewitness\Eye\App\Http\Controllers\FailedQueueController@delete');
    Route::get('failed_queue/retry/{id}',       'Eyewitness\Eye\App\Http\Controllers\FailedQueueController@retry');

    Route::get('composer',                      'Eyewitness\Eye\App\Http\Controllers\ComposerController@ping');

    Route::get('server',                        'Eyewitness\Eye\App\Http\Controllers\ServerController@ping');

    Route::get('log',                           'Eyewitness\Eye\App\Http\Controllers\LogController@index');
    Route::get('log/show',                      'Eyewitness\Eye\App\Http\Controllers\LogController@show');

    Route::get('scheduler/pause/{id}',          'Eyewitness\Eye\App\Http\Controllers\SchedulerController@pause');
    Route::get('scheduler/resume/{id}',         'Eyewitness\Eye\App\Http\Controllers\SchedulerController@resume');
    Route::get('scheduler/run',                 'Eyewitness\Eye\App\Http\Controllers\SchedulerController@run');
    Route::get('scheduler/delete/{mutex}',      'Eyewitness\Eye\App\Http\Controllers\SchedulerController@delete');
});


// Legacy
Route::group(['prefix' => 'api/eyewitness/v1', 'middleware' => 'eyewitness_auth'], function () {
    Route::get('{r1?}/{r2?}/{r3?}',             'Eyewitness\Eye\App\Http\Controllers\ServerController@moved');
});
