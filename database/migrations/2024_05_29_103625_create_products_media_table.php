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
        Schema::create('products_media', function (Blueprint $table) {
            $table->bigInteger('media_id', true, true);
            $table->unsignedInteger('media_product');
            $table->string('media_color', 8)->default('');
            $table->string('media_file', 64);
            $table->unsignedTinyInteger('media_type')->default('1');
            $table->unsignedSmallInteger('media_order')->default('0');
            $table->boolean('media_visible')->default('1');
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products_media');
    }
};
