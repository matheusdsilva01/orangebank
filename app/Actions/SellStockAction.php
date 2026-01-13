<?php

namespace App\Actions;

use App\Dto\SellStockDTO;

class SellStockAction
{
    public function handle(SellStockDTO $attributes): void
    {
        $accountStock = $attributes->accountStock;
        $stock = $attributes->stock;
        $profitLoss = $stock->current_price - $accountStock->purchase_price;
        $account = auth()->user()->investmentAccount;

        if ($profitLoss > 0) {
            // IR 15% sobre o lucro
            $tax = ($profitLoss * $accountStock->quantity) * config('finance.tax_ir_stock_profit');

            $salePrice = ($stock->current_price * $accountStock->quantity) - $tax;
            $account->balance += $salePrice * $accountStock->quantity;
        } else {
            $salePrice = $stock->current_price * $accountStock->quantity;
            $account->balance += $salePrice;
        }

        $accountStock->sale_price = $salePrice;
        $accountStock->sale_date = now();
        $account->save();
        $accountStock->save();
    }
}
