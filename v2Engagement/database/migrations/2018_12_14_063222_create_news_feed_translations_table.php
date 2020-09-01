<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewsFeedTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news_feed_translation', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('news_feed_id');
            $table->string('title', 255);
            $table->string('image_url', 255);
            $table->text('message');
            $table->string('link_text', 255);
            $table->string('language', 50);
            $table->boolean('is_deleted');
            $table->unsignedInteger('created_by');
            $table->unsignedInteger('updated_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('news_feed_translation');
    }
}
