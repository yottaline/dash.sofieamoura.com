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
        Schema::create('currencies', function (Blueprint $table) {
            $table->tinyInteger('currency_id', true, true);
            $table->string('currency_name', 120)->nullable();
            $table->string('currency_code', 3);
            $table->string('currency_symbol', 12);
            $table->boolean('currency_visible')->default('1');
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('currencies');
    }
};