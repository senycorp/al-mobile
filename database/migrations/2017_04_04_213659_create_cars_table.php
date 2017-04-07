<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateCarsTable
 */
class CreateCarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('chassis_number')->unique();
            $table->date('purchase_date');
            $table->decimal('purchase_price');
            $table->date('sale_date')->nullable();
            $table->decimal('sale_price')->nullable();
            $table->integer('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();

            $table->index('title');
            $table->index('chassis_number');
            $table->index('purchase_date');
            $table->index('purchase_price');
            $table->index('sale_date');
            $table->index('sale_price');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('cars');
    }
}
