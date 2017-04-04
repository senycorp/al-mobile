<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateInvoicesTable
 */
class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->nullable();
            $table->decimal('price');
            $table->text('description');

            $table->integer('car_id')->unsigned()->nullable();
            $table->foreign('car_id')->references('id')->on('cars')->onDelete('set null');
            $table->integer('invoice_type_id')->unsigned()->nullable();
            $table->foreign('invoice_type_id')->references('id')->on('invoice_types')->onDelete('set null');

            $table->date('date');
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
        Schema::drop('invoices');
    }
}
