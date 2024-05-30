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
        Schema::create('retailer_addresses', function (Blueprint $table) {
            $table->integer('address_id', true, true);
            $table->integer('address_retailer')->unsigned();
            $table->tinyInteger('address_type')->unsigned()->default('0');
            $table->integer('address_country')->unsigned();
            $table->string('address_province', 120);
            $table->string('address_city', 120);
            $table->string('address_zip', 24);
            $table->string('address_line1', 255);
            $table->string('address_line2', 255);
            $table->string('address_phone', 24);
            $table->string('address_note', 1024);
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('retailer_addresses');
    }
};