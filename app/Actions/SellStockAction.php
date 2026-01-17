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
        $hasProfit = $stock->current_price->isGreaterThan($accountStock->purchase_price);
        $investmentAccount = auth()->user()->investmentAccount;
        $purchasePrice = $accountStock->purchase_price;
        if ($hasProfit) {
            $profitPerShare = $stock->current_price->minus($purchasePrice);
            // IR 15% sobre o lucro
            $totalProfit = $profitPerShare->multipliedBy($stock->quantity);
            $incomeTax = $totalProfit->multipliedBy(config('finance.tax_ir_stock_profit'), RoundingMode::HALF_EVEN);

            $salePrice = $stock->current_price->multipliedBy($accountStock->quantity, RoundingMode::HALF_EVEN)->minus($incomeTax);

            $moneyAfterTax = $salePrice->multipliedBy($accountStock->quantity, RoundingMode::HALF_EVEN);
            $investmentAccount->credit($moneyAfterTax->getAmount()->toFloat());
        } else {
            $salePrice = $stock->current_price->multipliedBy($stock->quantity, RoundingMode::HALF_EVEN);
            $investmentAccount->credit($salePrice->getAmount()->toFloat());
        }

        $accountStock->sale_price = $salePrice;
        $accountStock->sale_date = now();
        $investmentAccount->save();
        $accountStock->save();
    }
}
