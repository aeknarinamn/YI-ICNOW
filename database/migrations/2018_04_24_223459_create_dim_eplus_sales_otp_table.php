<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDimEplusSalesOtpTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dim_eplus_sales_otp', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('eplus_salesman_register_data_id');
            $table->string('otp');
            $table->string('otp_ref');
            $table->string('phone_number');
            $table->boolean('is_active')->default(0);
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
        Schema::dropIfExists('dim_eplus_sales_otp');
    }
}
