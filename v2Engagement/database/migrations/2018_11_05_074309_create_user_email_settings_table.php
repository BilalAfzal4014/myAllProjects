<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserEmailSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_email_settings', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->unsignedInteger('company_id');
            $table->string('host', 100);
            $table->char('port', 5);
            $table->string('username', 100);
            $table->string('password', 100);
            $table->enum('encryption', ['ssl', 'tls'])->nullable();
            $table->string('from_email', 255);
            $table->string('from_name', 255);
            $table->timestamps();

            $table->foreign('company_id')->references('id')
                ->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_email_settings');
    }
}
