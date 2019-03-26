<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDimEplusSalesmanRegisterMappingDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dim_eplus_salesman_register_mapping_data', function (Blueprint $table) {
            $table->increments('id');
            $table->string('banner_code')->nullable();
            $table->string('banner_master')->nullable();
            $table->string('banner_e_plus')->nullable();
            $table->string('customer_tel')->nullable();
            $table->string('asm')->nullable();
            $table->string('fs_salesman_code')->nullable();
            $table->string('salesman_name')->nullable();
            $table->string('salesman_tel')->nullable();
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
        Schema::dropIfExists('dim_eplus_salesman_register_mapping_data');
    }
}
