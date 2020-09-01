<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampaignTemplate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaign_template', function (Blueprint $table) {

            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('company_id')->unsigned();
            $table->string('title', 255);
            $table->text('content');
            $table->enum('type', ['PUSH', 'EMAIL', 'BANNER', 'DIALOGUE', 'FULL SCREEN']);
            $table->string('thumbNail', 255)->nullable();

            $table->integer('created_by');
            $table->dateTime('created_at');
            $table->integer('modified_by');
            $table->dateTime('modified_at');

            $table->foreign('company_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('campaign_template');
    }
}
