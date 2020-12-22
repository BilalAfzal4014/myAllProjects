<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('company_key', 255)->nullable();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('logo', 255)->nullable();
            $table->string('timezone', 100)->nullable();
            $table->tinyInteger('is_active')->default(1);
            $table->rememberToken();
            $table->text('api_token')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->enum('cache_status', ["inprocess" , "completed"])->default('completed');
            $table->timestamp('last_login')->nullable();
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
        Schema::dropIfExists('users');
    }
}