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

        \App\Models\Size::insert([
            [
                'size_name' => '2 Yr',
                'size_order' => 1,
            ],
            [
                'size_name' => '3 Yr',
                'size_order' => 2,
            ],
            [
                'size_name' => '4 Yr',
                'size_order' => 3,
            ],
            [
                'size_name' => '5 Yr',
                'size_order' => 4,
            ],
            [
                'size_name' => '6 Yr',
                'size_order' => 5,
            ],
            [
                'size_name' => '8 Yr',
                'size_order' => 6,
            ],
            [
                'size_name' => '10 Yr',
                'size_order' => 7,
            ],
            [
                'size_name' => '12 Yr',
                'size_order' => 8,
            ],
            [
                'size_name' => '14 Yr',
                'size_order' => 9,
            ],
            [
                'size_name' => '16 Yr',
                'size_order' => 10,
            ],
        ]);
    }
}
