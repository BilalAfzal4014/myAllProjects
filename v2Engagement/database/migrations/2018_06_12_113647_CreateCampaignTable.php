<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampaignTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaign', function (Blueprint $table) {

            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('company_id')->nullable()->unsigned();
            $table->string('code', 50)->nullable();
            $table->string('name', 150)->nullable();
            $table->text('tags')->nullable();
            $table->integer('type_id')->nullable();
            $table->enum('step', ['GENERAL','COMPOSE','TARGET','DELIVERY','CONVERSION','PREVIEW']);
            $table->dateTime('start_time')->nullable();
            $table->dateTime('end_time')->nullable();
            $table->enum('schedule_type', ['DAILY','WEEEKLY','ONCE'])->nullable();
            $table->string('subject', 255)->nullable();
            $table->string('from_email', 50)->nullable();
            $table->string('from_name', 50)->nullable();
            $table->longText('en')->nullable();
            $table->longText('ar')->nullable();
            $table->longText('rs')->nullable();
            $table->longText('ca')->nullable();
            $table->enum('status', ['active', 'inactive', 'draft', 'expired', 'suspended'])->default('draft');
            $table->text('action_one')->nullable();
            $table->text('action_two')->nullable();
            $table->integer('action_target_id')->nullable();
            $table->string('action_target_value', 50)->nullable();
            $table->integer('trigger_point_id')->nullable();
            $table->string('target_link', 50)->nullable();
            $table->integer('message_type_id')->unsigned()->nullable();
            $table->integer('orientation_id')->unsigned()->nullable();
            $table->integer('position_id')->unsigned()->nullable();//messaging_position
            $table->integer('platform_id')->unsigned()->nullable();//messaging_platform
            $table->integer('location_id')->unsigned()->nullable();
            $table->enum('delivery_type', ['schedule','action'])->nullable();
            $table->boolean('is_deleted')->default(0)->nullable();
            $table->integer('created_by')->nullable();
            $table->dateTime('created_at')->nullable();
            $table->integer('updated_by')->nullable();
            $table->dateTime('updated_at')->nullable();

            $table->foreign('company_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('message_type_id')->references('id')->on('lookup')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('orientation_id')->references('id')->on('lookup')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('position_id')->references('id')->on('lookup')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('platform_id')->references('id')->on('lookup')
                ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('campaign');
    }
}
