<?php

Route::group(['prefix' => 'eyewitness_api/v1', 'middleware' => 'eyewitness_auth', 'namespace' => 'Eyewitness\Eye\App\Http\Controllers'], function () {
    Route::get('failed_queue',                  'FailedQueueController@index');
    Route::get('failed_queue/delete/all',       'FailedQueueController@delete_all');
    Route::get('failed_queue/delete/{id}',      'FailedQueueController@delete');
    Route::get('failed_queue/retry/{id}',       'FailedQueueController@retry');

    Route::get('composer',                      'ComposerController@ping');

    Route::get('server',                        'ServerController@ping');

    Route::get('log',                           'LogController@index');
    Route::get('log/show',                      'LogController@show');

    Route::get('scheduler/event/run',           'SchedulerController@run');
    Route::get('scheduler/event/pause',         'SchedulerController@pause');
    Route::get('scheduler/event/resume',        'SchedulerController@resume');
    Route::get('scheduler/event/forget_mutex',  'SchedulerController@forgetMutex');
});


// Legacy
Route::group(['prefix' => 'api/eyewitness/v1', 'middleware' => 'eyewitness_auth'], function () {
    Route::get('{r1?}/{r2?}/{r3?}',             'Eyewitness\Eye\App\Http\Controllers\ServerController@moved');
});
