<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDimIcnowMiniUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dim_icnow_mini_user', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('line_user_id')->nullable();
            $table->string('username',100)->nullable();
            $table->string('password',100)->nullable();
            $table->string('mini_code',50)->nullable();
            $table->string('dt_code',50)->nullable();
            $table->string('mini_name',100)->nullable();
            $table->string('dt_name',100)->nullable();
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
        Schema::dropIfExists('dim_icnow_mini_user');
    }
}
