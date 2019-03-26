<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDimCampaignSetTaskToSendMessageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dim_campaign_set_task_to_send_message', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('campaign_id');
            $table->string('type');
            $table->boolean('check_status');
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
        Schema::dropIfExists('dim_campaign_set_task_to_send_message');
    }
}
