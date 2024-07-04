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
        Schema::create('ws_orders_products', function (Blueprint $table) {
            $table->integer('ordprod_id', true, true);
            $table->integer('ordprod_order')->unsigned();
            $table->integer('ordprod_product')->unsigned();
            $table->integer('ordprod_color')->unsigned();
            $table->bigInteger('ordprod_size')->unsigned();
            $table->smallInteger('ordprod_request_qty')->unsigned();
            $table->smallInteger('ordprod_served_qty')->unsigned();
            $table->decimal('ordprod_price', 9, 2);
            $table->decimal('ordprod_subtotal', 9, 2)->default('0');
            $table->decimal('ordprod_total', 9, 2);
            $table->tinyInteger('ordprod_discount')->unsigned()->default('0');
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ws_orders_products');
    }
};
