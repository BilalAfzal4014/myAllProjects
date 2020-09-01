<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampaignSegments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaign_segments', function (Blueprint $table) {

          $table->engine = 'InnoDB';
          $table->integer('campaign_id')->unsigned();
          $table->integer('segment_id')->unsigned();

          $table->foreign('campaign_id')->references('id')->on('campaign');
          $table->foreign('segment_id')->references('id')->on('segment');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('campaign_segments');
    }
}
