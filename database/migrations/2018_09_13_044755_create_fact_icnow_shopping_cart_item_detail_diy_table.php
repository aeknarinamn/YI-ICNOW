<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFactIcnowShoppingCartItemDetailDiyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fact_icnow_shopping_cart_item_detail_diy', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('shopping_cart_item_id');
            $table->integer('person_in_party')->nullable();
            $table->string('product_focus',50)->nullable();
            $table->string('comment',255)->nullable();
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
        Schema::dropIfExists('fact_icnow_shopping_cart_item_detail_diy');
    }
}
