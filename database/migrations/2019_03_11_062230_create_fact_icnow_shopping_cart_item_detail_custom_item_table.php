<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFactIcnowShoppingCartItemDetailCustomItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fact_icnow_shopping_cart_item_detail_custom_item', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('shopping_cart_item_custom_id');
            $table->string('item_name',100)->nullable();
            $table->integer('item_value')->nullable();
            $table->decimal('price',2)->nullable();
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
        Schema::dropIfExists('fact_icnow_shopping_cart_item_detail_custom_item');
    }
}
