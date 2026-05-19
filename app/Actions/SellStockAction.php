<?php

namespace App\Actions;

use App\Dto\SellStockDTO;
use Brick\Math\RoundingMode;

class SellStockAction
{
    public function handle(SellStockDTO $attributes): void
    {
        $accountStock = $attributes->accountStock;
        $stock = $attributes->stock;
        $account = $accountStock->account;
        $tax = config('finance.tax_ir_stock_profit'); // 15%

        $investedValue = $accountStock->purchase_price->multipliedBy($accountStock->quantity, RoundingMode::HALF_EVEN);
        $currentValue = $stock->current_price->multipliedBy($accountStock->quantity, RoundingMode::HALF_EVEN);
        
        $profit = $currentValue->minus($investedValue);
        $amountToCredit = $currentValue;

        if ($profit->isGreaterThan(0)) {
            $taxAmount = $profit->multipliedBy($tax, RoundingMode::HALF_EVEN);
            $amountToCredit = $currentValue->minus($taxAmount);
        }

        $account->credit($amountToCredit);

        $accountStock->sale_price = $stock->current_price;
        $accountStock->sale_date = now();
        $accountStock->save();
    }
}
