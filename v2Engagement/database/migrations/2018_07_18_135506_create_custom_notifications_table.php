<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email')->nullable();
            $table->string('firebase_key')->nullable();
            $table->string('device_key')->nullable();
            $table->longText('payload');
            $table->string('job', 40)->nullable();
            $table->enum('status', ['added', 'executing', 'completed', 'failed'])
                ->default('added')->nullable();
            $table->boolean('sent')->default(false);
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
        });

        Schema::create('notifications_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('notification_id')->unsigned();
            $table->char('status', 10);
            $table->text('message');
            $table->timestamps();

            $table->foreign('notification_id')->references('id')
                ->on('notifications')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifications_logs');
        Schema::dropIfExists('notifications');
    }
}
