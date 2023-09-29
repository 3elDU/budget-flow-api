<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Budget;
use App\Models\Category;
use App\Models\Expense;
use App\Models\Income;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $expense_categories = Category::factory()
            ->count(3)
            ->sequence(
                ['name' => 'Food'],
                ['name' => 'Travel'],
                ['name' => 'Entertainment'],
            )
            ->create();
        $income_categories = Category::factory()->count(2)->sequence(
            ['name' => 'Work'],
            ['name' => 'Loan'],
        )->create();

        $user = User::factory()->create();
        $budget = Budget::factory()->hasAttached($user)->create();

        Income::factory(10)
            ->for($user)
            ->for($budget)
            ->create()
            // Attach a random income category for each income
            ->each(fn (Income $income) => $income->categories()->attach($income_categories->toQuery()->inRandomOrder()->first()));

        Expense::factory(10)
            ->for($user)
            ->for($budget)
            ->create()
            // Attach a random expense category for each expense
            ->each(fn (Expense $expense) => $expense->categories()->attach($expense_categories->toQuery()->inRandomOrder()->first()));
    }
}
