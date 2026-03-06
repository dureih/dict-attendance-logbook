<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@dict.gov.ph'],
            [
                'name'     => 'Administrator',
                'email'    => 'admin@dict.gov.ph',
                'password' => Hash::make('dict2024'),
            ]
        );
    }
}
