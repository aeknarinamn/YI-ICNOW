<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFactIcnowCustomerShippingAddressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fact_icnow_customer_shipping_address', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('line_user_id');
            $table->string('first_name',100)->nullable();
            $table->string('last_name',100)->nullable();
            $table->string('address',100)->nullable();
            $table->string('sub_district',100)->nullable();
            $table->string('district',100)->nullable();
            $table->string('province',100)->nullable();
            $table->string('post_code',10)->nullable();
            $table->string('phone_number',50)->nullable();
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
        Schema::dropIfExists('fact_icnow_customer_shipping_address');
    }
}
