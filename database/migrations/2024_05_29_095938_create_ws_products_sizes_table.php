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
        Schema::create('ws_products_sizes', function (Blueprint $table) {
            $table->bigInteger('prodsize_id', true, true);
            $table->unsignedInteger('prodsize_product');
            $table->unsignedInteger('prodsize_size');
            $table->string('prodsize_color', 45);
            $table->decimal('prodsize_cost', 9, 2)->default('0.00');
            $table->decimal('prodsize_wsp', 9, 2)->default('0.00');
            $table->decimal('prodsize_rrp', 9, 2)->default('0.00');
            $table->unsignedInteger('prodsize_qty')->default('0');
            $table->unsignedInteger('prodsize_stock')->default('0');
            $table->boolean('prodsize_visible')->default('1');
            $table->integer('prodsize_modified_by')->unsigned()->nullable();
            $table->dateTime('prodsize_modified')->nullable();
            $table->integer('prodsize_created_by')->unsigned();
            $table->dateTime('prodsize_created');
            // $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ws_products_sizes');
    }
};
