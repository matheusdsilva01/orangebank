<?php

use App\Models\Account\InvestmentAccount;
use App\Models\Stock;
use App\Models\User;

test('should calculate stock volatility and create history', function (): void {
    //  Prepare

    $stock = Stock::factory()->create(['current_price' => 10000]);

    //  Act
    $firstVariation = $stock->calculateVolatility();

    //  Assert
    $this->assertDatabaseHas(
        'stock_histories',
        [
            'stock_id' => $stock->id,
            'daily_variation' => $firstVariation,
        ]);
});

test('should update stock daily_variation', function (): void {
    //  Prepare
    $stock = Stock::factory()->create(['current_price' => 10000]);

    //  Act
    $volatility = $stock->calculateVolatility();

    //  Assert
    expect($stock->refresh()->daily_variation)->toBe((string) $volatility);
});
