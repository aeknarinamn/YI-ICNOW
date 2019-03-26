<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDimIcnowProductCustomTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dim_icnow_product_custom', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('icnow_product_id');
            $table->string('group_name',100)->nullable();
            $table->integer('volumn')->default(0);
            $table->integer('unit')->default(0);
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
        Schema::dropIfExists('dim_icnow_product_custom');
    }
}
