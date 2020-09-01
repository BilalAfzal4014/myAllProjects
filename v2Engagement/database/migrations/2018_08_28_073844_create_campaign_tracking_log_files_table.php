<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampaignTrackingLogFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaign_tracking_log_files', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('campaign_id')->unsigned();
            $table->integer('campaign_tracking_id')->unsigned();
            $table->integer('queue_id')->unsigned()->default(0);
            $table->text('log')->nullable();
            $table->timestamps();

            $table->foreign('campaign_tracking_id')->references('id')->on('campaign_tracking');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('campaign_tracking_log_files');
    }
}
