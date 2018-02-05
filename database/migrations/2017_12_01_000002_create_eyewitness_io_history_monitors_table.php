<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEyewitnessIoHistoryMonitorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(config('eyewitness.eyewitness_database_connection'))->create('eyewitness_io_history_monitors', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type');
            $table->string('meta');
            $table->string('value')->nullable()->default(null);
            $table->text('record');
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
        Schema::connection(config('eyewitness.eyewitness_database_connection'))->dropIfExists('eyewitness_io_history_monitors');
    }
}
