<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewsFeedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news_feed', function (Blueprint $table) {

            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('company_id')->nullable()->unsigned();
            $table->integer('segment_id')->nullable()->unsigned();
            $table->integer('location_id')->nullable()->unsigned();
            $table->integer('news_feed_template_id')->nullable()->unsigned();
            $table->string('name', 255)->nullable();
            $table->string('title', 255)->nullable();
            $table->text('description')->nullable();
            $table->enum('link_type_ios', ['DEEPLINK', 'WEBLINK'])->nullable();
            $table->string('ios_url', 255)->nullable();
            $table->enum('link_type_android', ['DEEPLINK', 'WEBLINK'])->nullable();
            $table->string('android_url', 255)->nullable();
            $table->enum('link_type_window', ['DEEPLINK', 'WEBLINK'])->nullable();
            $table->string('window_url', 255)->nullable();
            $table->enum('link_type_web', ['DEEPLINK', 'WEBLINK'])->nullable();
            $table->string('web_url', 255)->nullable();
            $table->text('tags')->nullable();
            $table->text('content')->nullable();
            $table->enum('category', ['News','Advertising','Announcements','Social'])->nullable();
            $table->enum('step', ['COMPOSE', 'DELIVERY', 'CONFIRM']);
            $table->tinyInteger('publish_now')->nullable();
            $table->dateTime('start_time')->nullable();
            $table->dateTime('end_time')->nullable();
            $table->tinyInteger('enable_end_time')->nullable();
            $table->tinyInteger('enable_timezone')->nullable();
            $table->tinyInteger('is_active')->nullable();
            $table->tinyInteger('is_deleted')->nullable();
            $table->integer('created_by')->nullable();
            $table->dateTime('created_at')->nullable();
            $table->integer('updated_by')->nullable();
            $table->dateTime('updated_at')->nullable();

            $table->foreign('company_id')->references('id')->on('users');
            $table->foreign('segment_id')->references('id')->on('segment');
            $table->foreign('location_id')->references('id')->on('location');
            $table->foreign('news_feed_template_id')->references('id')->on('news_feed_template');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('news_feed');
    }
}
