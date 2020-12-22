<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBoardQueueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('board_queue', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('board_id');
            $table->enum('status', ['Available', 'Processing','Complete','Failed']);
            $table->enum('priority', ['1', '2','3']);
            $table->text('detail');
            $table->text('error_message');
            $table->date('start_at');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('board_queue');
    }
}
