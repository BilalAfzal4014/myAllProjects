<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToCampaignActionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('campaign_action', function (Blueprint $table) {
            $table->enum('action_type', ['trigger', 'conversion']);
            $table->tinyInteger('validity')->default(0);
            $table->enum('period', ['minute', 'hour', 'day'])->default('day');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('campaign_action', function (Blueprint $table) {
            $table->dropColumn(['action_type', 'validity', 'period']);
        });
    }
}
