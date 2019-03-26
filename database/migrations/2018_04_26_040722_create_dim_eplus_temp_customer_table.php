<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDimEplusTempCustomerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dim_eplus_temp_customer', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('seq')->nullable();
            $table->string('type')->nullable();
            $table->text('display')->nullable();
            $table->text('playload')->nullable();
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
        Schema::dropIfExists('dim_eplus_temp_customer');
    }
}
