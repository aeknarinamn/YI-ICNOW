<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFactEcommerceLogSendMessageDtConfirmationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fact_ecommerce_log_send_message_dt_confirmation', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id');
            $table->integer('ecommerce_customer_id');
            $table->integer('dt_id');
            $table->string('type');
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
        Schema::dropIfExists('fact_ecommerce_log_send_message_dt_confirmation');
    }
}
