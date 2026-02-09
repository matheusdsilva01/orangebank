<?php

namespace Database\Factories;

use App\Models\Goal;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class GoalFactory extends Factory
{
    protected $model = Goal::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'description' => $this->faker->text(),
            'attributes' => $this->faker->randomElement([
                ['action' => 'login'],
                ['action' => 'buy_stock'],
                ['action' => 'sell_stock'],
                ['action' => 'deposit'],
                ['action' => 'withdraw'],
                ['action' => 'transfer'],
            ]),
            'threshold' => $this->faker->numberBetween(1, 5),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
