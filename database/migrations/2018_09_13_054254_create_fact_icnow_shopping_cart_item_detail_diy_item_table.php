<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFactIcnowShoppingCartItemDetailDiyItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fact_icnow_shopping_cart_item_detail_diy_item', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('shopping_cart_item_detail_diy_id');
            $table->string('value',100)->nullable();
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
        Schema::dropIfExists('fact_icnow_shopping_cart_item_detail_diy_item');
    }
}
