<?php

namespace Database\Factories;

use App\Models\Stock;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends Factory<Stock>
 */
class StockFactory extends Factory
{
    protected $model = Stock::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'symbol' => $this->faker->word(),
            'sector' => $this->faker->word(),
            'current_price' => (string) $this->faker->numberBetween(100000, 2000000),
            'daily_variation' => $this->faker->numberBetween(0.5, 0.9),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
