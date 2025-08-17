<?php

namespace Tests\Unit\Stock;

use App\Models\Account;
use App\Models\Stock;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttachStockToAccount extends TestCase
{
    use RefreshDatabase;

    public function test_attach_stock_to_account()
    {
        //  Prepare
        $user = User::factory()->create();
        $account = Account::factory()->recycle($user)->createInvestment()->create();
        $stock = Stock::factory()->create();
        //  Act
        $account->stocks()->attach($stock, [
            'purchase_price' => $stock->current_price,
            'quantity' => 20,
        ]);
        //  Assert
        $this->assertDatabaseHas('account_stock', ['account_id' => $account->id, 'stock_id' => $stock->id, 'quantity' => 20]);
        $this->assertDatabaseCount('account_stock', 1);
    }
}
