<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImportDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('import_data', function (Blueprint $table) {

            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('company_id')->unsigned();
            $table->string('actual_file_name', 50);
            $table->string('file_name', 50);
            $table->string('file_size', 50)->nullable();
            $table->tinyInteger('is_processed')->default(0);
            $table->tinyInteger('is_deleted')->default(0);
            $table->dateTime('process_date')->nullable();
            $table->integer('created_by')->nullable();
            $table->dateTime('created_at')->nullable();

            $table->foreign('company_id')->references('id')->on('users');
        });

        Schema::table('attribute_data', function (Blueprint $table) {
            $table->foreign('import_data_id')->references('id')->on('import_data');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();

        Schema::dropIfExists('import_data');

        Schema::enableForeignKeyConstraints();


    }
}
