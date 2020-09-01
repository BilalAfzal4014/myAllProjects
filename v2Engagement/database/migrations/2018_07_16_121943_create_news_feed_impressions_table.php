<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewsFeedImpressionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news_feed_impressions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('row_id')->unsigned()->nullable();
            $table->integer('user_id')->unsigned()->nullable();
            $table->integer('news_feed_id')->unsigned()->nullable();
            $table->integer('location_id')->unsigned()->nullable();
            $table->string('platform', 20)->nullable();
            $table->boolean('viewed')->default(0);
            $table->timestamp('created_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('news_feed_impressions');
    }
}
