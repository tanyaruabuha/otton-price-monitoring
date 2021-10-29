<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('number')->unique();
            $table->string('name');
            $table->string('rd')->nullable();
            $table->string('wd1')->nullable();
            $table->string('wd2')->nullable();
            $table->string('PCF_SMASHINN')->nullable();
            $table->string('PCF_TENNISWA')->nullable();
            $table->string('PCF_STRINGER')->nullable();
            $table->string('PCF_TENNISPO')->nullable();
            $table->string('PCF_APOLLOLE')->nullable();
            $table->string('PCF_TENNISNU')->nullable();
            $table->string('PCF_FRAMEWOR')->nullable();
            $table->string('PCF_TWUSA')->nullable();
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
        Schema::dropIfExists('products');
    }
}
