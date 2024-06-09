<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ws_orders', function (Blueprint $table) {
            $table->integer('order_id', true, true);
            $table->string('order_code', 12)->unique();
            $table->unsignedTinyInteger('order_season');
            $table->integer('order_retailer')->unsigned();
            $table->decimal('order_tax', 9, 2)->default('0.00');
            $table->decimal('order_shipping', 9, 2)->default('0.00');
            $table->decimal('order_subtotal', 9, 2);
            $table->tinyInteger('order_discount')->unsigned()->default('0');
            $table->decimal('order_total', 9, 2);
            $table->tinyInteger('order_currency')->unsigned();
            $table->tinyInteger('order_type')->unsigned()->default('0');
            $table->tinyInteger('order_status')->unsigned()->default('0');
            $table->string('order_note', 1024)->nullable();
            $table->integer('order_bill_country')->unsigned();
            $table->string('order_bill_province', 120)->nullable();
            $table->string('order_bill_city', 120);
            $table->string('order_bill_zip', 24);
            $table->string('order_bill_line1', 255);
            $table->string('order_bill_line2', 255);
            $table->string('order_bill_phone', 24);
            $table->integer('order_ship_country')->unsigned();
            $table->string('order_ship_province', 120)->nullable();
            $table->string('order_ship_city', 120);
            $table->string('order_ship_zip', 24);
            $table->string('order_ship_line1', 255);
            $table->string('order_ship_line2', 255);
            $table->string('order_ship_phone', 24);
            $table->string('order_proforma', 42)->nullable();
            $table->dateTime('order_proformatime')->nullable();
            $table->string('order_invoice', 42)->nullable();
            $table->dateTime('order_invoicetime')->nullable();
            $table->boolean('order_skip_adv')->default('0');
            $table->dateTime('order_modified')->nullable();
            $table->dateTime('order_placed')->nullable();
            $table->dateTime('order_created');
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ws_orders');
    }
};