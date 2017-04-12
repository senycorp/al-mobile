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
            $table->text('description')->nullable();

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('car_id')->unsigned()->nullable();
            $table->foreign('car_id')->references('id')->on('cars')->onDelete('cascade');
            $table->integer('invoice_type_id')->unsigned()->nullable();
            $table->foreign('invoice_type_id')->references('id')->on('invoice_types')->onDelete('set null');
            $table->boolean('purchase_invoice')->nullable()->default(0);
            $table->boolean('sale_invoice')->nullable()->default(0);
            $table->boolean('account')->nullable()->default(0);
            $table->longText('invoice_data')->nullable();
            $table->date('date');
            $table->boolean('tax')->default(false);
            $table->timestamps();

            $table->index('title');
            $table->index('price');
            $table->index('date');
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
