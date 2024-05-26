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
        Schema::create('locations', function (Blueprint $table) {
            $table->integer('location_id', true, true);
            $table->string('location_name', 30);
            $table->string('location_iso_2', 2)->comment('ISO code alpha-2');
            $table->string('location_iso_3', 3)->comment('ISO code alpha-3');
            $table->string('location_code', 6)->comment('phone code');
            $table->boolean('location_visible')->default('1');
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};