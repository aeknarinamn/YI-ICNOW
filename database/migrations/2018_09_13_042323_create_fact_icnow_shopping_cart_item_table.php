<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFactIcnowShoppingCartItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fact_icnow_shopping_cart_item', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('shopping_cart_id');
            $table->integer('line_user_id');
            $table->integer('product_id');
            $table->string('product_name',100)->nullable();
            $table->integer('section_id')->nullable();
            $table->string('product_desc',255)->nullable();
            $table->string('sku',20)->nullable();
            $table->decimal('price',10,2)->nullable();
            $table->decimal('special_price',10,2)->nullable();
            $table->dateTime('special_start_date')->nullable();
            $table->dateTime('special_end_date')->nullable();
            $table->decimal('retial_price',10,2)->nullable();
            $table->integer('quantity')->nullable();
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
        Schema::dropIfExists('fact_icnow_shopping_cart_item');
    }
}
