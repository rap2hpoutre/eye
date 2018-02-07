<?php

use Eyewitness\Eye\Eye;
use Illuminate\Support\Facades\Route;

Route::group([
        'prefix' => config('eyewitness.base_uri', 'eyewitness'),
        'middleware' => (Eye::laravelVersionIs('>=', '5.2.0') ? [config('eyewitness.route_middleware'), 'eyewitness_auth'] : ['eyewitness_auth']),
        'namespace' => 'Eyewitness\Eye\Http\Controllers'
], function () {
    Route::get('dashboard',                    ['as' => 'eyewitness.dashboard',                         'uses' => 'DashboardController@index']);

    Route::get('schedulers/{id}',              ['as' => 'eyewitness.schedulers.show',                   'uses' => 'SchedulerController@show']);
    Route::put('schedulers/{id}',              ['as' => 'eyewitness.schedulers.update',                 'uses' => 'SchedulerController@update']);
    Route::delete('schedulers/{id}',           ['as' => 'eyewitness.schedulers.destroy',                'uses' => 'SchedulerController@destroy']);

    Route::get('settings',                     ['as' => 'eyewitness.settings.index',                    'uses' => 'Settings\DisplayController@index']);

    Route::put('severity',                     ['as' => 'eyewitness.severity.update',                   'uses' => 'Settings\SeverityController@update']);

    Route::get('recipients/create',            ['as' => 'eyewitness.recipients.create',                 'uses' => 'Settings\RecipientController@create']);
    Route::delete('recipients/{id}',           ['as' => 'eyewitness.recipients.destroy',                'uses' => 'Settings\RecipientController@destroy']);
    Route::post('recipients/{id}/test',        ['as' => 'eyewitness.recipients.test',                   'uses' => 'Settings\RecipientController@sendTest']);

    Route::post('recipients/create/email',     ['as' => 'eyewitness.recipients.create.email',           'uses' => 'Settings\RecipientController@email']);
    Route::post('recipients/create/slack',     ['as' => 'eyewitness.recipients.create.slack',           'uses' => 'Settings\RecipientController@slack']);
    Route::post('recipients/create/nexmo',     ['as' => 'eyewitness.recipients.create.nexmo',           'uses' => 'Settings\RecipientController@nexmo']);
    Route::post('recipients/create/hipchat',   ['as' => 'eyewitness.recipients.create.hipchat',         'uses' => 'Settings\RecipientController@hipchat']);
    Route::post('recipients/create/webhook',   ['as' => 'eyewitness.recipients.create.webhook',         'uses' => 'Settings\RecipientController@webhook']);
    Route::post('recipients/create/pushover',  ['as' => 'eyewitness.recipients.create.pushover',        'uses' => 'Settings\RecipientController@pushover']);
    Route::post('recipients/create/pagerduty', ['as' => 'eyewitness.recipients.create.pagerduty',       'uses' => 'Settings\RecipientController@pagerduty']);

    Route::get('notifications/{id}',           ['as' => 'eyewitness.notifications.show',                'uses' => 'NotificationsController@show']);
    Route::put('notifications/{id}',           ['as' => 'eyewitness.notifications.update',              'uses' => 'NotificationsController@update']);

    Route::get('dns',                          ['as' => 'eyewitness.dns.show',                          'uses' => 'DnsController@show']);

    Route::get('queues/{id}',                  ['as' => 'eyewitness.queues.show',                       'uses' => 'QueueController@show']);
    Route::put('queues/{id}',                  ['as' => 'eyewitness.queues.update',                     'uses' => 'QueueController@update']);
    Route::delete('queues/{id}',               ['as' => 'eyewitness.queues.destroy',                    'uses' => 'QueueController@destroy']);

    Route::get('failedjob/{queue_id}/{job_id}',     ['as' => 'eyewitness.failedjob.show',               'uses' => 'FailedJobController@show']);
    Route::post('failedjob/{queue_id}/{job_id}',    ['as' => 'eyewitness.failedjob.retry',              'uses' => 'FailedJobController@retry']);
    Route::delete('failedjob/{queue_id}/{job_id}',  ['as' => 'eyewitness.failedjob.destroy',            'uses' => 'FailedJobController@destroy']);
    Route::post('failedjob/{queue_id}',             ['as' => 'eyewitness.failedjob.retry-all',          'uses' => 'FailedJobController@retryAll']);
    Route::delete('failedjob/{queue_id}',           ['as' => 'eyewitness.failedjob.destroy-all',        'uses' => 'FailedJobController@destroyAll']);
});
