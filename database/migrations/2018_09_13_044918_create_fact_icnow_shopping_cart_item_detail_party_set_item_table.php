<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFactIcnowShoppingCartItemDetailPartySetItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fact_icnow_shopping_cart_item_detail_party_set_item', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('shopping_cart_item_party_set_id');
            $table->string('item_name',100)->nullable();
            $table->integer('item_value')->nullable();
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
        Schema::dropIfExists('fact_icnow_shopping_cart_item_detail_party_set_item');
    }
}
