<?php

namespace Database\Factories;

use App\Models\Account;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Account>
 */
class AccountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'balance' => fake()->randomFloat(2, 0, 10000),
            'type' => fake()->randomElement(['current', 'investment']),
        ];
    }

    public function createCurrent(): Factory
    {
        return $this->state([
            'type' => 'current',
        ]);
    }

    public function createInvestment(): static
    {
        return $this->state([
            'type' => 'investment',
        ]);
    }
}
