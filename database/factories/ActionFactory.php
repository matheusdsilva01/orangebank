<?php

namespace Database\Factories;

use App\Models\Action;
use App\Models\Stock;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ActionFactory extends Factory
{
    protected $model = Action::class;

    public function definition(): array
    {
        $entityClass = $this->faker->randomElement([User::class, Stock::class]);
        $attributesType = $entityClass::class === User::class
            ? ['type' => 'deposit', 'amount' => $this->faker->numberBetween(100, 10000)]
            : ['type' => 'buy', 'symbol' => $entityClass->symbol, 'quantity' => 1];

        return [
            'attributes' => $attributesType,
            'entity_id' => $entityClass::factory(),
            'entity_type' => $entityClass,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
