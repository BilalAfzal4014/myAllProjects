<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBoardVariantTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('board_variant', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('variant_type', ['email', 'inapp','push']);
            $table->integer('board_id');
            $table->tinyInteger('distribution');
            $table->integer('message_type_id');
            $table->integer('orientation_id');
            $table->integer('position_id');
            $table->integer('platform_id');
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
        Schema::dropIfExists('board_variant');
    }
}
