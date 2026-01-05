<?php

use App\Models\Account\InvestmentAccount;
use App\Models\Stock;
use App\Models\User;

test('should calculate stock volatility and create history', function (): void {
    //  Prepare
    $this->artisan('app:seed-stocks');
    $user = User::factory()->create();

    $account = InvestmentAccount::factory()->recycle($user)->create();

    $stock = Stock::query()->where('symbol', 'BOIB3')->first();
    $oldPrice = $stock->current_price;
    $account->stocks()->attach($stock->id, ['quantity' => 10, 'purchase_price' => $stock->current_price, 'purchase_date' => now()]);

    //  Act
    $firstVariation = $stock->calculateVolatility();

    //  Assert
    $new = $firstVariation > 0 ? round($oldPrice * $firstVariation, PHP_ROUND_HALF_EVEN) : round($oldPrice / ($firstVariation * -1), PHP_ROUND_HALF_EVEN);
    $this->assertDatabaseHas(
        'stock_histories',
        [
            'stock_id' => $stock->id,
            'daily_variation' => $firstVariation,
            'daily_price' => $new,
        ]);
});
