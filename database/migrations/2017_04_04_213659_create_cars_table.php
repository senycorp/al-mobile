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
            $table->timestamps();

            $table->index(['title', 'chassis_number', 'sale_date']);
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
