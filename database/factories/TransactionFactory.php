<?php

namespace Database\Factories;

use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = fake()->randomElement(['internal', 'external']);
        $tax = $type === 'internal' ? 0 : 5;

        return [
            'from_account_id' => Account::factory(),
            'to_account_id' => Account::factory(),
            'amount' => fake()->randomFloat(2, 1, 100),
            'type' => $type,
            'tax' => $tax,
        ];
    }
}
