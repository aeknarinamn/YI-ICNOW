<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFactIcnowCustomerOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fact_icnow_customer_order', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('line_user_id');
            $table->string('order_no',50)->nullable();
            $table->integer('shopping_cart_id')->nullable();
            $table->integer('address_id')->nullable();
            $table->string('date_of_delivery',30)->nullable();
            $table->string('time_of_delivery',50)->nullable();
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
        Schema::dropIfExists('fact_icnow_customer_order');
    }
}
