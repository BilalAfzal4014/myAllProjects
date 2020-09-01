<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttributeDataBackup extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attribute_data_backup', function (Blueprint $table) {

            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('company_id')->unsigned();
            $table->integer('row_id');
            $table->string('code', 50);
            $table->text('value');
            $table->integer('import_data_id')->unsigned()->nullable();
            $table->enum('data_type', [
                'conversion',
                'action',
                'user',
                'app',
                'gamification',
                'custom'
            ])->default('user');

            $table->integer('created_by');
            $table->dateTime('created_at');
            $table->integer('updated_by');
            $table->dateTime('updated_at');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attribute_data_backup');
    }
}
