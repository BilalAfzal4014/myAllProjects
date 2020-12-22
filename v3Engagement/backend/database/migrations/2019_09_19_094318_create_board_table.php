<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBoardTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('board', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('app_group_id');
            $table->string('tags');
            $table->string('code');
            $table->string('name');
            $table->enum('step', ['general', 'delivery','target','setting','preview']);
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->enum('status', ['active', 'draft','expired','suspended']);
            $table->enum('schedule_type', ['daily', 'weekly','monthly','once']);
            $table->enum('delivery_type', ['schedule', 'action','api']);
            $table->enum('priority', ['low', 'medium','high']);
            $table->integer('action_trigger_delay_value');
            $table->enum('action_trigger_delay_unit', ['second', 'minutes','hour']);
            $table->tinyInteger('delivery_control');
            $table->integer('delivery_control_delay_value');
            $table->enum('delivery_control_delay_unit',['minutes', 'day','week','month']);
            $table->tinyInteger('capping');
            $table->integer('created_by');
            $table->integer('updated_by');
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
        Schema::dropIfExists('board');
    }
}
