<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDimEplusSalesmanRegisterDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dim_eplus_salesman_register_data', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('line_user_id');
            $table->string('fs_salesman_code')->nullable();
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
        Schema::dropIfExists('dim_eplus_salesman_register_data');
    }
}
