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
        Schema::create('retailers', function (Blueprint $table) {
            $table->integer('retailer_id', true, true);
            $table->string('retailer_code', 8)->unique();
            $table->string('retailer_fullName', 255);
            $table->string('retailer_email', 120)->unique();
            $table->string('retailer_password', 255);
            $table->string('retailer_phone', 24)->unique();
            $table->string('retailer_company', 120);
            $table->string('retailer_logo', 64)->nullable();
            $table->string('retailer_desc', 1024)->nullable();
            $table->string('retailer_website', 255)->nullable();
            $table->integer('retailer_country', false, true);
            $table->string('retailer_province', 120);
            $table->string('retailer_city', 120);
            $table->string('retailer_address', 255)->nullable();
            $table->integer('retailer_billAdd', false, true)->nullable();
            $table->integer('retailer_shipAdd', false, true)->nullable();
            $table->tinyInteger('retailer_currency', false, true)->default('1');
            $table->tinyInteger('retailer_adv_payment')->default('40');
            $table->dateTime('retailer_approved')->nullable();
            $table->integer('retailer_approved_by', false, true)->nullable();
            $table->boolean('retailer_blocked')->default('0');
            $table->dateTime('retailer_modified')->nullable();
            $table->dateTime('retailer_created');
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('retailers');
    }
};