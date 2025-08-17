<?php

namespace Tests\Feature\Stock;

use App\Models\Account;
use App\Models\Stock;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StockVolatilityTest extends TestCase
{
    use RefreshDatabase;

    //  Refatorar a modelagem de stocks, talvez para armazenar o valor da ação no dia da compra
    //  e não current_price, pq porra, como que eu vou calcular a volatilidade de uma ação que comprei
    //  e o quanto eu ganhei/perdi se o current_price dela muda toda a hora?
    public function test_should_calculate_stock_volatility_and_create_history(): void
    {
        //  Prepare
        $this->artisan('app:seed-stocks');
        $user = User::factory()->create();

        $account = Account::factory()->recycle($user)->createInvestment()->create();

        $stock = Stock::query()->where('symbol', 'BOIB3')->first();
        $oldPrice = $stock->current_price;
        $account->stocks()->attach($stock->id, ['quantity' => 10, 'purchase_price' => $stock->current_price, 'purchase_date' => now()]);

        //  Act
        $firstVariation = $stock->calculateVolatility();

        //  Assert
        $this->assertDatabaseHas(
            'stock_histories',
            [
                'stock_id' => $stock->id,
                'daily_variation' => round($firstVariation, 1),
                'daily_price' => round($oldPrice * (1 + $firstVariation / 100), 2),
            ]);
    }
}
