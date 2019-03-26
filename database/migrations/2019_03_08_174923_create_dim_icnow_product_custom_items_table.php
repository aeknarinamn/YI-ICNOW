<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDimIcnowProductCustomItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dim_icnow_product_custom_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('icnow_product_custom_set_id');
            $table->integer('default_unit')->default(0);
            $table->string('value',100)->nullable();
            $table->decimal('price',10,2)->nullable();
            $table->string('img_url',255)->nullable();
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
        Schema::dropIfExists('dim_icnow_product_custom_items');
    }
}
