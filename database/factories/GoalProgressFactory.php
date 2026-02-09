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
            'progress' => $this->faker->numberBetween(0, 10),
            'entity_id' => User::factory(),
            'entity_type' => User::class,
            'completed' => false,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'goal_id' => Goal::factory(),
        ];
    }
}
