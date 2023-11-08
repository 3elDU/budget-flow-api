<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Budget;
use App\Models\Convert;
use App\Models\Category;
use App\Models\Operation;
use Illuminate\Database\Seeder;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $expenseCategories = Category::factory()
            ->count(3)
            ->sequence(
                ['name' => 'Food'],
                ['name' => 'Travel'],
                ['name' => 'Entertainment'],
            )
            ->create();
        $incomeCategories = Category::factory()->count(2)->sequence(
            ['name' => 'Work'],
            ['name' => 'Loan'],
        )->create();

        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => 'password'
        ]);
        $user->settings()->create();
        $budget = Budget::factory()->hasAttached($user)->create();

        Operation::factory(10)
            ->for($user)
            ->for($budget)
            ->create()
            // Attach a random category for each operation
            // If the amount is positive, attach an income category.
            // If the amount is negative, attach an expense category.
            ->each(fn (Operation $operation) =>
            $operation->categories()->attach(
                $operation->amount > 0
                    ? $incomeCategories->toQuery()->inRandomOrder()->first()
                    : $expenseCategories->toQuery()->inRandomOrder()->first()
            ));

        Convert::create([
            'currency_from' => 'USD',
            'currency_to' => 'UAH',
            'rate' => 37
        ]);
        Convert::create([
            'currency_from' => 'EUR',
            'currency_to' => 'UAH',
            'rate' => 40
        ]);
    }
}
