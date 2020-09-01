<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConsoleJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('console_jobs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('interval');
            $table->boolean('enabled')->default(true);
            $table->integer('retry_count')->unsigned()->default(0);
            $table->integer('instance_count')->unsigned()->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('console_jobs');
    }
}
