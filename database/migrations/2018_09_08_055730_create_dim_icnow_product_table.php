<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDimIcnowProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dim_icnow_product', function (Blueprint $table) {
            $table->increments('id');
            $table->string('product_name',100)->nullable();
            $table->integer('section_id')->nullable();
            $table->string('product_desc',255)->nullable();
            $table->string('sku',20)->nullable();
            $table->decimal('price',10,2)->nullable();
            $table->decimal('special_price',10,2)->nullable();
            $table->dateTime('special_start_date')->nullable();
            $table->dateTime('special_end_date')->nullable();
            $table->boolean('is_active')->nullable();
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
        Schema::dropIfExists('dim_icnow_product');
    }
}
