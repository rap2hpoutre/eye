<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEyewitnessIoNotificationRecipientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(config('eyewitness.eyewitness_database_connection'))->create('eyewitness_io_notification_recipients', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type', 191)->index();
            $table->string('address', 191);
            $table->string('meta', 191)->nullable()->default(null);
            $table->boolean('low')->default(true)->index();
            $table->boolean('medium')->default(true)->index();
            $table->boolean('high')->default(true)->index();
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
        Schema::connection(config('eyewitness.eyewitness_database_connection'))->dropIfExists('eyewitness_io_notification_recipients');
    }
}
