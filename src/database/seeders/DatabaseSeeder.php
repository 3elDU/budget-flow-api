<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Add basic data to DB, such as admin user, etc.
        $this->call(BasicDataSeeder::class);

        if (app()->environment('local')) {
            $this->call(TestDataSeeder::class);
        }
    }
}
