<?php

use App\Models\Account\InvestmentAccount;
use App\Models\Stock;
use App\Models\User;

test('buy stock', function (): void {
    //  Prepare
    $user = User::factory()->create();
    $account = InvestmentAccount::factory()->for($user)->create(['balance' => '100000']);
    $stock = Stock::factory()->create([
        'current_price' => '15000',
        'daily_variation' => 0.02,
    ]);

    //  Act
    $this->actingAs($user);
    $this->post(route('stock.buy', ['stock' => $stock->id]), [
        'quantity' => 1,
    ]);

    //  Assert
    $this->assertDatabaseHas('account_stock', ['account_id' => $account->id, 'stock_id' => $stock->id, 'quantity' => 1]);
    $this->assertDatabaseCount('account_stock', 1);
});
