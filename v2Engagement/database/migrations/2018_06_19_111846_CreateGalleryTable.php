<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGalleryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gallery', function (Blueprint $table) {

            $table->increments('id');
            $table->integer('company_id')->nullable()->unsigned();
            $table->string('image_url', 255)->nullable();
            $table->string('image_name', 255)->nullable();
            $table->tinyInteger('is_active')->default(1);
            $table->tinyInteger('is_deleted')->default(0);

            $table->integer('created_by');
            $table->dateTime('created_at');
            $table->integer('updated_by');
            $table->dateTime('updated_at');

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
        Schema::drop('gallery');
    }
}
