<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDimEplusCustomerOtpLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dim_eplus_customer_otp_log', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('eplus_customer_register_data_id');
            $table->string('otp');
            $table->string('otp_ref');
            $table->string('phone_number');
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
        Schema::dropIfExists('dim_eplus_customer_otp_log');
    }
}
