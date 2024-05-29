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
        Schema::create('ws_products', function (Blueprint $table) {
            $table->integer('product_id', true, true);
            $table->string('product_ref', 24);
            $table->string('product_code', 24)->nullable();
            $table->string('product_name', 255);
            $table->string('product_desc', 1024);
            $table->bigInteger('product_media', false, true)->nullable();
            $table->tinyInteger('product_season', false, true);
            $table->tinyInteger('product_type', false, true)->default('2')->comment('1:Babies, 2:Kids, 3:Teens, 4:Adults');
            $table->tinyInteger('product_gender', false, true)->default('1')->comment('0:Both, 1:Girl, 2:Boy');
            $table->integer('product_category', false, true);
            $table->tinyInteger('product_ordertype', false, true)->comment('1:IN-STOCK, 2:PRE-ORDER');
            $table->smallInteger('product_minqty', false, true)->comment('MIN ORDER QUANTITY');
            $table->smallInteger('product_maxqty', false, true);
            $table->smallInteger('product_mincolorqty', false, true)->default('0');
            $table->decimal('product_minorder', 9, 2)->default('0.00');
            $table->tinyInteger('product_discount', false, true)->default('0');
            $table->boolean('product_freeshipping')->default('0');
            $table->string('product_delivery', 120);
            $table->integer('product_views', false, true)->default('0');
            $table->integer('product_order', false, true)->default('0');
            $table->string('product_related', 1024);
            $table->boolean('product_published')->default('0');
            $table->integer('product_modified_by', false, true)->nullable();
            $table->dateTime('product_modified')->nullable();
            $table->integer('product_created_by', false, true);
            $table->dateTime('product_created');
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ws_products');
    }
};