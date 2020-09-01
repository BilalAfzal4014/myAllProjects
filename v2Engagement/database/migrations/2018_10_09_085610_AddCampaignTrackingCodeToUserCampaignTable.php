<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCampaignTrackingCodeToUserCampaignTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_campaign', function (Blueprint $table) {
            $table->string('campaign_code', 40)->nullable()->after('campaign_id');
            $table->string('track_key', 40)->nullable()->after('campaign_code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_campaign', function (Blueprint $table) {
            $table->dropColumn(['campaign_code', 'track_key']);
        });
    }
}
