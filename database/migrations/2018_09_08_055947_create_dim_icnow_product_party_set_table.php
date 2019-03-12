<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDimIcnowProductPartySetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dim_icnow_product_party_set', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('icnow_product_id');
            $table->string('group_name',100)->nullable();
            $table->integer('volumn')->nullable();
            $table->string('unit',50)->nullable();
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
        Schema::dropIfExists('dim_icnow_product_party_set');
    }
}
