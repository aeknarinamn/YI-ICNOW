<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFactIcnowCustomerOrderHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fact_icnow_customer_order_history', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id')->nullable();
            $table->string('order_no',50)->nullable();
            $table->integer('user_id')->nullable();
            $table->string('email',100)->nullable();
            $table->string('username',100)->nullable();
            $table->string('from_status',100)->nullable();
            $table->string('to_status',100)->nullable();
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
        Schema::dropIfExists('fact_icnow_customer_order_history');
    }
}
