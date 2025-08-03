<?php

namespace Database\Factories;

use App\Enums\AccountType;
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
            'balance' => fake()->randomFloat(2, 100, 10000),
            'number' => strtoupper(fake()->lexify('????????????')),
            'type' => fake()->randomElement(AccountType::class)
        ];
    }

    public function createCurrent(): Factory|AccountFactory
    {
        return $this->state([
            'type' => AccountType::Current
        ]);
    }

    public function createInvestment(): Factory|AccountFactory
    {
        return $this->state([
            'type' => AccountType::Investment,
        ]);
    }
}
