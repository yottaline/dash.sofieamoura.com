<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    function run(): void
    {
        \App\Models\User::create([
            'user_code' => Str::random(8),
            'user_name' => 'Tech.Support',
            'user_email' => 'support@yottaline.com',
            'user_password' => Hash::make('Support@Yottaline'),
            'user_created' => now()
        ]);

        \App\Models\Currency::insert([
            [
                'currency_id' => '1',
                'currency_name' => 'Euro',
                'currency_code' => 'EUR',
                'currency_symbol' => '&euro;',
            ], [
                'currency_id' => '2',
                'currency_name' => 'US Doller',
                'currency_code' => 'USD',
                'currency_symbol' => '&dollar;',
            ],
        ]);
    }
}
