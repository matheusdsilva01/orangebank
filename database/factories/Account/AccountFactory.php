<?php

namespace Database\Factories\Account;

use App\Models\Account\CurrentAccount;
use App\Models\Account\InvestmentAccount;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<InvestmentAccount|CurrentAccount>
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
            'balance' => (string) fake()->numberBetween(10000000, 80000000), // between 10.000,00 and 80.000,00
            'number' => strtoupper(fake()->lexify('????????????')),
        ];
    }
}
