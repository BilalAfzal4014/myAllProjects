<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexUserAttributeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_attribute', function (Blueprint $table) {
            $table->index(['company_id', 'user_id', 'email', 'device_type'], 'CLUSTER_IDX');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_attribute', function (Blueprint $table) {
            $table->dropIndex('CLUSTER_IDX');
        });
    }
}
