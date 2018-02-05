<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEyewitnessIoSchedulersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(config('eyewitness.eyewitness_database_connection'))->create('eyewitness_io_schedulers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('schedule');
            $table->string('command')->index();
            $table->string('timezone')->nullable()->default(null);
            $table->boolean('without_overlapping')->default(false);
            $table->boolean('run_in_background')->default(false);
            $table->boolean('on_one_server')->default(false);
            $table->string('mutex')->unique()->index();
            $table->boolean('healthy')->nullable()->default(null)->index();
            $table->timestamp('next_run_due')->useCurrent()->index();
            $table->timestamp('next_check_due')->useCurrent()->index();
            $table->boolean('alert_on_missed')->default(true)->index();
            $table->boolean('alert_on_fail')->default(true);
            $table->integer('alert_run_time_greater_than')->default(0);
            $table->integer('alert_run_time_less_than')->default(0);
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection(config('eyewitness.eyewitness_database_connection'))->dropIfExists('eyewitness_io_schedulers');
    }
}
