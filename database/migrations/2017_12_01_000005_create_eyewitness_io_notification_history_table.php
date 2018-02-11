<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEyewitnessIoNotificationHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(config('eyewitness.eyewitness_database_connection'))->create('eyewitness_io_notification_history', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type', 191)->index();
            $table->boolean('isError');
            $table->string('title', 191);
            $table->text('description');
            $table->string('severity', 191);
            $table->text('meta')->nullable()->default(null);
            $table->boolean('acknowledged')->default(false);
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
        Schema::connection(config('eyewitness.eyewitness_database_connection'))->dropIfExists('eyewitness_io_notification_history');
    }
}
