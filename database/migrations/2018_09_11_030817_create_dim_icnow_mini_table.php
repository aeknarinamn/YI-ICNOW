<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDimIcnowMiniTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dim_icnow_mini', function (Blueprint $table) {
            $table->increments('id');
            $table->string('dt_code',20)->nullable();
            $table->string('dt_name',100)->nullable();
            $table->string('mini_code',20)->nullable();
            $table->string('mini_name',100)->nullable();
            $table->string('walls_code',20)->nullable();
            $table->string('walls_name',100)->nullable();
            $table->string('latitude',20)->nullable();
            $table->string('longitude',20)->nullable();
            $table->string('address',250)->nullable();
            $table->string('customer_name',100)->nullable();
            $table->string('customer_phonenumber',20)->nullable();
            $table->boolean('is_active')->nullable();
            $table->string('login_url',255)->nullable();
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
        Schema::dropIfExists('dim_icnow_mini');
    }
}
