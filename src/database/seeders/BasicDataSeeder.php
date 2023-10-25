<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class BasicDataSeeder extends Seeder
{
    public function run(): void
    {
        if (User::where('email', env('ADMIN_USER_EMAIL'))->exists() === false) {
            User::firstOrCreate([
                'name' => 'Admin',
                'email' => env('ADMIN_USER_EMAIL'),
                'password' => env('ADMIN_USER_PASSWORD')
            ]);
        }
    }
}
