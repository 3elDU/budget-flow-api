<?php

namespace Database\Factories;

use Brick\Math\Exception\NumberFormatException;
use Brick\Math\Exception\RoundingNecessaryException;
use Brick\Money\Exception\UnknownCurrencyException;
use Brick\Money\Money;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Operation>
 */
class OperationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     * @throws RoundingNecessaryException
     * @throws NumberFormatException
     * @throws UnknownCurrencyException
     */
    public function definition(): array
    {
        return [
            'amount' => Money::of(rand(-150, 150), 'USD'),
            'name' => fake()->words(2, true),
            'description' => fake()->sentence(),
        ];
    }
}
