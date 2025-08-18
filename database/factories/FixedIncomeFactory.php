<?php

namespace Database\Factories;

use App\Enums\FixedIncomeType;
use App\Models\FixedIncome;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class FixedIncomeFactory extends Factory
{
    protected $model = FixedIncome::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'type' => $this->faker->randomElement(FixedIncomeType::class),
            'rate' => $this->faker->randomFloat(3, 0, 0.2),
            'rateType' => $this->faker->word(),
            'maturity' => Carbon::now()->addDays(60),
            'minimumInvestment' => $this->faker->randomFloat(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
