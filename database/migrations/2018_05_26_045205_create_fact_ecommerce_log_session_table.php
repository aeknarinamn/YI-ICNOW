<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFactEcommerceLogSessionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fact_ecommerce_log_session', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('line_user_id');
            $table->integer('ecommerce_customer_id')->nullable();
            $table->integer('order_id')->nullable();
            $table->boolean('is_product_views')->default(0);
            $table->boolean('is_add_to_cart')->default(0);
            $table->boolean('is_check_out')->default(0);
            $table->boolean('is_transaction')->default(0);
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
        Schema::dropIfExists('fact_ecommerce_log_session');
    }
}
