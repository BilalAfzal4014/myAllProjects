<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampaignTrackingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaign_tracking', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('campaign_id');
            $table->integer('row_id');
            $table->integer('app_user_token_id');
            $table->integer('variant_id');
            $table->string('email');
            $table->text('firebase_key');
            $table->text('device_key');
            $table->enum('device_type', config('enums.migration.campaign_tracking.device_type'))->default('android');
            $table->text('payload');
            $table->string('track_key');
            $table->string('job');
            $table->enum('status', config('enums.migration.campaign_tracking.status'))->default('added');
            $table->tinyInteger('sent');
            $table->tinyInteger('viewed');
            $table->timestamp('started_at')->default('2019-01-01');
            $table->timestamp('ended_at')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('viewed_at')->nullable();
            $table->timestamps();
            $table->foreign('campaign_id')->references('id')
                ->on('campaign')->onUpdate('cascade')->onDelete('cascade');

        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('campaign_tracking');
    }
}
