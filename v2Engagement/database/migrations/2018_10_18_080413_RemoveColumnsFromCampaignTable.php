<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveColumnsFromCampaignTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('campaign', function (Blueprint $table) {
            $table->dropColumn(['conversion_validaty', 'conversion_type_id', 'conversion_value']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('campaign', function (Blueprint $table) {
            $table->integer('conversion_validaty')->nullable();
            $table->integer('conversion_type_id')->nullable();
            $table->string('conversion_value', 50)->nullable();
        });
    }
}
