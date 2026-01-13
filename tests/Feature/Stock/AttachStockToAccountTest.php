<?php

use App\Models\Account\InvestmentAccount;
use App\Models\Stock;
use App\Models\User;

test('attach stock to account', function (): void {
    //  Prepare
    $user = User::factory()->create();
    $account = InvestmentAccount::factory()->recycle($user)->create();
    $stock = Stock::factory()->create();

    //  Act
    $account->stocks()->attach($stock, [
        'purchase_price' => $stock->current_price,
        'quantity' => 20,
    ]);

    //  Assert
    $this->assertDatabaseHas('account_stock', ['account_id' => $account->id, 'stock_id' => $stock->id, 'quantity' => 20]);
    $this->assertDatabaseCount('account_stock', 1);
});
