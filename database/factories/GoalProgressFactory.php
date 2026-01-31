<?php

namespace Database\Factories;

use App\Models\Goal;
use App\Models\GoalProgress;
use App\Models\Stock;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class GoalProgressFactory extends Factory
{
    protected $model = GoalProgress::class;

    public function definition(): array
    {
        return [
            'progress' => $this->faker->randomNumber(),
            'entity_id' => $this->faker->randomElement([User::factory(), Stock::factory()]),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'goal_id' => Goal::factory(),
        ];
    }
}
