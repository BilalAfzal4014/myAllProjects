<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLookUpTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lookup', function (Blueprint $table) {

            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('company_id')->nullable()->unsigned();
            $table->string('code', 100);
            $table->string('name', 100);
            $table->text('description');
            $table->integer('parent_id');
            $table->tinyInteger('is_deleted')->default(0);
            $table->tinyInteger('modifiedby');

            $table->dateTime('created_date');
            $table->dateTime('modified_date');

            $table->foreign('company_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lookup');
    }
}
