<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class BasicDataSeeder extends Seeder
{
    public function run(): void
    {
        $adminEmail = env('ADMIN_USER_EMAIL', 'admin@example.com');

        if (User::where('email', $adminEmail)->exists() === false) {
            User::firstOrCreate([
                'name' => 'Admin',
                'email' => $adminEmail,
                'password' => env('ADMIN_USER_PASSWORD', 'password')
            ]);
        }
    }
}
