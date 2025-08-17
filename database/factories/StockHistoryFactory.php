<?php

namespace Database\Factories;

use App\Models\Stock;
use App\Models\StockHistory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class StockHistoryFactory extends Factory
{
    protected $model = StockHistory::class;

    public function definition(): array
    {
        return [
            'daily_variation' => $this->faker->randomFloat(),
            'daily_price' => $this->faker->randomFloat(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'stock_id' => Stock::factory(),
        ];
    }
}
