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
        Schema::create('ws_products_colors', function (Blueprint $table) {
            $table->integer('prodcolor_id', true, true);
            $table->string('prodcolor_ref', 24)->unique();
            // $table->string('prodcolor_code', 12);
            $table->string('prodcolor_name', 24);
            $table->integer('prodcolor_product')->unsigned();
            $table->smallInteger('prodcolor_mincolorqty')->default('0');
            $table->smallInteger('prodcolor_minqty')->default('0');
            $table->smallInteger('prodcolor_maxqty')->default('0');
            $table->decimal('prodcolor_minorder', 9, 2)->default('0.00');
            $table->bigInteger('prodcolor_media')->unsigned()->nullable();
            $table->tinyInteger('prodcolor_ordertype')->unsigned()->default('2');
            $table->tinyInteger('prodcolor_discount')->unsigned()->default('0');
            $table->boolean('prodcolor_freeshipping')->default('0');
            $table->string('prodcolor_related', 1024);
            $table->integer('prodcolor_views')->unsigned()->default('0');
            $table->integer('prodcolor_order')->unsigned();
            $table->boolean('prodcolor_published')->default('0');
            $table->integer('prodcolor_modified_by')->unsigned()->nullable();
            $table->dateTime('prodcolor_modified')->nullable();
            $table->integer('prodcolor_created_by')->unsigned();
            $table->dateTime('prodcolor_created');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ws_products_colors');
    }
};