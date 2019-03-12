<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFactIcnowShoppingCartItemDetailCustomTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fact_icnow_shopping_cart_item_detail_custom', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('shopping_cart_item_id');
            $table->string('group_name',100)->nullable();
            $table->integer('choose_item')->nullable();
            $table->integer('max_item')->nullable();
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
        Schema::dropIfExists('fact_icnow_shopping_cart_item_detail_custom');
    }
}
