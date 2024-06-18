<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    function up(): void
    {
        Schema::create('ws_products', function (Blueprint $table) {
            $table->integer('product_id', true, true);
            $table->string('product_ref', 24)->unique();
            $table->string('product_code', 24);
            $table->string('product_name', 255);
            $table->string('product_desc', 1024)->nullable();
            $table->unsignedInteger('product_season');
            $table->tinyInteger('product_type', false, true)->default('2')->comment('1:Babies, 2:Kids, 3:Teens, 4:Adults');
            $table->tinyInteger('product_gender', false, true)->default('1')->comment('0:Both, 1:Girl, 2:Boy');
            $table->unsignedInteger('product_category');
            $table->boolean('product_merged_colors')->default('0');
            $table->string('product_delivery', 120)->nullable();
            $table->integer('product_modified_by', false, true)->nullable();
            $table->datetime('product_modified')->nullable();
            $table->unsignedInteger('product_created_by');
            $table->datetime('product_created');

            $table->foreign('product_season')->references('season_id')->on('seasons')->cascadeOnDelete();
            $table->foreign('product_category')->references('category_id')->on('categories')->cascadeOnDelete();
        });
    }

    function down(): void
    {
        Schema::dropIfExists('ws_products');
    }
};
