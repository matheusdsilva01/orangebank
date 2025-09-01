<?php

namespace Database\Factories;

use App\Enums\TransactionType;
use App\Models\Account\Account;
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
        $type = fake()->randomElement(TransactionType::class);
        $tax = $type === TransactionType::External ? 5 : 0;

        return [
            'from_account_id' => Account::factory()->create()->id,
            'to_account_id' => Account::factory()->create()->id,
            'amount' => fake()->randomFloat(2, 1, 100),
            'type' => $type,
            'tax' => $tax,
        ];
    }
}
