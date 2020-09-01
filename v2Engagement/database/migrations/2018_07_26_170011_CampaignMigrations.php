<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CampaignMigrations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('campaign_action', function (Blueprint $table) {

            $table->integer('campaign_id')->unsigned();
            $table->integer('action_id')->unsigned();
            $table->string('value', 250);
            $table->enum('action_type', ['trigger', 'conversion']);
            $table->tinyInteger('validity')->default(0);
            $table->enum('period', ['minute', 'hour', 'day'])->default('day');

            $table->foreign('campaign_id')->references('id')
                ->on('campaign')->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('action_id')->references('id')
                ->on('lookup')->onUpdate('cascade')->onDelete('cascade');

        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('campaign_action');
    }
}
