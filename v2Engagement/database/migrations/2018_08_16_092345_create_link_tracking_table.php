<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLinkTrackingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('link_tracking', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('row_id')->unsigned()->nullable();
            $table->integer('rec_id')->unsigned()->nullable();
            $table->enum('rec_type', ['Email', 'Newsfeed'])->nullable();
            $table->text('actual_url')->nullable();
            $table->string('ip_address', 50)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('device', 50)->nullable();
            $table->boolean('viewed')->default(false);
            $table->dateTime('created_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('link_tracking');
    }
}
