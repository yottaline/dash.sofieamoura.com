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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            // $table->integer('user_id', true, true);
            $table->string('user_code', 8);
            $table->string('user_name', 120);
            $table->string('user_email', 120)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('user_password', 255);
            $table->dateTime('user_login')->nullable();
            $table->dateTime('user_modified')->nullable();
            $table->dateTime('user_created');
            $table->rememberToken();
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};