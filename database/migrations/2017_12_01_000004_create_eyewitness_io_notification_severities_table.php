<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Eyewitness\Eye\Repo\Notifications\Severity;

class CreateEyewitnessIoNotificationSeveritiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(config('eyewitness.eyewitness_database_connection'))->create('eyewitness_io_notification_severities', function (Blueprint $table) {
            $table->increments('id');
            $table->string('namespace')->index();
            $table->string('notification')->index();
            $table->string('severity');
        });

        Severity::insert([
            ['namespace' => 'Scheduler', 'notification' => 'Fast', 'severity' => 'medium'],
            ['namespace' => 'Scheduler', 'notification' => 'Slow', 'severity' => 'medium'],
            ['namespace' => 'Scheduler', 'notification' => 'Missed', 'severity' => 'high'],
            ['namespace' => 'Scheduler', 'notification' => 'Overdue', 'severity' => 'high'],
            ['namespace' => 'Scheduler', 'notification' => 'Error', 'severity' => 'high'],
            ['namespace' => 'Scheduler', 'notification' => 'Working', 'severity' => 'high'],

            ['namespace' => 'DNS', 'notification' => 'Change', 'severity' => 'low'],

            ['namespace' => 'SSL', 'notification' => 'GradeChange', 'severity' => 'medium'],
            ['namespace' => 'SSL', 'notification' => 'Expiring', 'severity' => 'low'],
            ['namespace' => 'SSL', 'notification' => 'Invalid', 'severity' => 'high'],
            ['namespace' => 'SSL', 'notification' => 'Revoked', 'severity' => 'high'],

            ['namespace' => 'Debug', 'notification' => 'Enabled', 'severity' => 'high'],
            ['namespace' => 'Debug', 'notification' => 'Disabled', 'severity' => 'high'],

            ['namespace' => 'Composer', 'notification' => 'Safe', 'severity' => 'medium'],
            ['namespace' => 'Composer', 'notification' => 'Risk', 'severity' => 'medium'],

            ['namespace' => 'Database', 'notification' => 'Offline', 'severity' => 'high'],
            ['namespace' => 'Database', 'notification' => 'Online', 'severity' => 'high'],
            ['namespace' => 'Database', 'notification' => 'SizeOk', 'severity' => 'low'],
            ['namespace' => 'Database', 'notification' => 'SizeLarge', 'severity' => 'low'],
            ['namespace' => 'Database', 'notification' => 'SizeSmall', 'severity' => 'low'],

            ['namespace' => 'Queue', 'notification' => 'Failed', 'severity' => 'medium'],
            ['namespace' => 'Queue', 'notification' => 'Offline', 'severity' => 'high'],
            ['namespace' => 'Queue', 'notification' => 'Online', 'severity' => 'high'],
            ['namespace' => 'Queue', 'notification' => 'FailedCountExceeded', 'severity' => 'medium'],
            ['namespace' => 'Queue', 'notification' => 'FailedCountOk', 'severity' => 'medium'],
            ['namespace' => 'Queue', 'notification' => 'PendingCountExceeded', 'severity' => 'medium'],
            ['namespace' => 'Queue', 'notification' => 'PendingCountOk', 'severity' => 'medium'],
            ['namespace' => 'Queue', 'notification' => 'WaitLong', 'severity' => 'medium'],
            ['namespace' => 'Queue', 'notification' => 'WaitOk', 'severity' => 'medium'],

            ['namespace' => 'Custom', 'notification' => 'Passed', 'severity' => 'medium'],
            ['namespace' => 'Custom', 'notification' => 'Failed', 'severity' => 'medium'],

        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection(config('eyewitness.eyewitness_database_connection'))->dropIfExists('eyewitness_io_notification_severities');
    }
}
