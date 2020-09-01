<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserCampaign extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_campaign', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('company_id')->unsigned()->nullable();
            $table->string('app_name', 100);
            $table->integer('app_id')->unsigned()->nullable();
            $table->integer('row_id')->unsigned()->nullable();
            $table->integer('user_id')->unsigned()->nullable();
            $table->integer('campaign_id')->unsigned()->nullable();
            $table->string('campaign_code', 40)->nullable();
            $table->string('track_key', 40)->nullable();
            $table->integer('event_id')->unsigned()->nullable();
            $table->string('event_value', 100);
            $table->enum('platform', ['IOS', 'ANDROID', 'WEB']);
            $table->enum('rec_type', ['conversion', 'action_trigger']);
            $table->string('build', 100);
            $table->string('version', 100);
            $table->dateTime('campaign_receive_date');
            $table->dateTime('created_at');

            $table->foreign('company_id')->references('id')->on('users');
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
        Schema::dropIfExists('user_campaign');
    }
}
