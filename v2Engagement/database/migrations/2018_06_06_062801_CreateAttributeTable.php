<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttributeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attribute', function (Blueprint $table) {

            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('company_id')->unsigned();
            $table->string('code', 100);
            $table->enum('type', ['General', 'Custom']);
            $table->string('name', 100);
            $table->string('alias', 100);
            $table->enum('data_type', ['INT', 'VARCHAR', 'SELECT', 'DATE']);
            $table->string('length', 100);
            $table->string('source_table_name', 100);
            $table->string('value_column', 255);
            $table->string('text_column', 255);
            $table->string('where_condition', 255);
            $table->tinyInteger('is_deleted');

            $table->integer('created_by');
            $table->dateTime('created_at');
            $table->integer('updated_by');
            $table->dateTime('updated_at');

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
        Schema::dropIfExists('attribute');
    }
}
