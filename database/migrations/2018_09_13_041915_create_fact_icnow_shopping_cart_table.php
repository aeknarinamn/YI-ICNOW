<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFactIcnowShoppingCartTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fact_icnow_shopping_cart', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('line_user_id');
            $table->boolean('is_active')->nullable();
            $table->boolean('is_product_update')->nullable();
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
        Schema::dropIfExists('fact_icnow_shopping_cart');
    }
}
