<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_attribute', function (Blueprint $table) {
            $table->unsignedBigInteger('row_id', true);
            $table->unsignedInteger('company_id');
            $table->unsignedInteger('user_id');
            $table->string('app_name', 255);
            $table->string('app_id', 255);
            $table->enum('device_type', ['ios', 'android', 'web']);
            $table->string('username', 50)->nullable();
            $table->string('firstname', 50)->nullable();
            $table->string('lastname', 50)->nullable();
            $table->string('device_token', 400)->nullable();
            $table->string('fire_base_key', 400)->nullable();
            $table->string('user_token', 400)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('timezone', 400)->nullable();
            $table->double('latitude')->nullable();
            $table->double('longitude')->nullable();
            $table->string('country', 50)->nullable();
            $table->string('lang', 5)->nullable();
            $table->string('version', 50)->nullable();
            $table->string('build', 50)->nullable();
            $table->timestamp('last_login')->default(\DB::raw("CURRENT_TIMESTAMP"));
            $table->boolean('is_active')->default(false);
            $table->boolean('is_login')->default(false);
            $table->boolean('locked')->default(false);
            $table->boolean('enabled')->default(false);
            $table->boolean('enable_notification')->default(true);
            $table->boolean('email_notification')->default(true);
            $table->boolean('test_mode')->default(false);
            $table->boolean('is_import')->default(false);

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
        Schema::dropIfExists('user_attribute');
    }
}
