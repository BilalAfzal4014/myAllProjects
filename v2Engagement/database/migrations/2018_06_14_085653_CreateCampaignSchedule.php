<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampaignSchedule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaign_schedule', function (Blueprint $table) {

            $table->engine = 'InnoDB';
            $table->integer('campaign_id')->unsigned();
            $table->enum('day', ['FRIDAY','THURSDAY','WEDNESDAY','TUESDAY','MONDAY','SUNDAY','SATURDAY']);

            $table->foreign('campaign_id')->references('id')->on('campaign');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('campaign_schedule');
    }
}
