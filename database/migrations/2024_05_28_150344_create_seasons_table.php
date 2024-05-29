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
        Schema::create('seasons', function (Blueprint $table) {
            $table->integer('season_id', true, true);
            $table->string('season_name', 120);
            $table->string('season_code', 12);
            $table->boolean('season_current')->default('0');
            $table->tinyInteger('season_adv_payment')->default('40');
            $table->string('season_adv_context', 512);
            $table->string('season_delivery_1', 512)->comment('FOR IN-STOCK ORDERS');
            $table->string('season_delivery_2', 512)->comment('FOR PRE-ORDER ORDERS');
            $table->dateTime('season_start');
            $table->dateTime('season_end')->comment('DEFAULT ENDS AFTER 2 MTHS');
            $table->string('season_lookbook', 120);
            $table->boolean('season_visible')->default('1');
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seasons');
    }
};