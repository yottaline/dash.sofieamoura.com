<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();
// dash.sofieamoura.com
        \App\Models\User::create([
            'user_code' => Str::random(7),
            'user_name' => 'Sof.Support',
            'user_email' => 'support@sofieamoura.com',
            'user_password' => Hash::make('Support@sofieamoura'),
            'user_created' => now()
        ]);
    }
}