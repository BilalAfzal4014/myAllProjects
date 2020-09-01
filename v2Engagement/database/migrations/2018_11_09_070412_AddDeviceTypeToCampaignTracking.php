<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDeviceTypeToCampaignTracking extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('campaign_tracking', function (Blueprint $table) {
            $table->enum('device_type', ['ANDROID', 'IOS', 'WEB'])->nullable()->after('device_key');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('campaign_tracking', function (Blueprint $table) {
            $table->dropColumn('device_type');
        });
    }
}
