<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampaignTrackingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaign_tracking', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('campaign_id')->unsigned();
            $table->integer('row_id')->unsigned();
            $table->string('email')->nullable();
            $table->string('firebase_key')->nullable();
            $table->string('device_key')->nullable();
            $table->enum('device_type', ['ANDROID', 'IOS', 'WEB'])->nullable();
            $table->longText('payload');
            $table->string('track_key', 40)->nullable();
            $table->string('job', 40)->nullable();
            $table->enum('status', ['added', 'executing', 'completed', 'failed'])
                ->default('added')->nullable();
            $table->boolean('sent')->default(false);
            $table->boolean('viewed')->default(false);
            $table->timestamps();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('viewed_at')->nullable();

            $table->foreign('campaign_id')->references('id')->on('campaign')
                ->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('campaign_tracking_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('campaign_tracking_id')->unsigned();
            $table->char('status', 10);
            $table->text('message');
            $table->timestamps();

            $table->foreign('campaign_tracking_id')->references('id')->on('campaign_tracking')
                ->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('campaign_tracking_logs');
        Schema::dropIfExists('campaign_tracking');
    }
}
