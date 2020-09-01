<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyAppsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id')->unsigned();
            $table->string('name', 50)->nullable();
            $table->string('logo', 50)->nullable();
            $table->string('app_id', 50)->nullable();
            $table->string('description', 50)->nullable();
            $table->string('ios_cert_live')->nullable();
            $table->string('ios_cert_dev')->nullable();
            $table->string('firebase_server_api_key')->nullable();
            $table->string('ios_passphrase')->nullable();
            $table->enum('platform', ['IOS', 'ANDROID', 'WEB'])->nullable();
            $table->boolean('is_sandbox')->default(false);
            $table->boolean('is_active')->default(false);
            $table->boolean('is_deleted')->default(false);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('modified_at')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('modified_by')->nullable();

            $table->foreign('company_id')
                ->references('id')->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('app');
    }
}
