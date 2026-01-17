<?php

namespace Database\Factories;

use App\Models\Account\InvestmentAccount;
use App\Models\FixedIncome;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AccountFixedIncome>
 */
class AccountFixedIncomeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'purchased_date' => now(),
            'sale_date' => null,
            'amount_investment' => (string) $this->faker->numberBetween(10000000, 200000000),
            'amount_earned' => (string) $this->faker->numberBetween($this->states->get('amount_investment'), 250000000),
            'fixed_income_id' => FixedIncome::factory()->create()->id,
            'account_id' => InvestmentAccount::factory()->create()->id,
        ];
    }
}
