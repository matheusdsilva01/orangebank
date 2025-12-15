<?php

namespace App\Actions;

class SellFixedIncomeAction
{
    public function handle(array $attributes): void
    {
        $fixedIncomePurchased = $attributes['fixedIncomePurchased'];
        $investmentAccount = $attributes['account'];
        // 22% IR on profit
        $value = ($fixedIncomePurchased->amount_earned - $fixedIncomePurchased->amount_investment) * 0.22;
        // truncate to 4 decimal places
        $taxOnProfit = floor($value * 10000) / 10000;
        $fixedIncomePurchased->sale_date = now();
        $fixedIncomePurchased->save();

        $moneyEarned = $fixedIncomePurchased->amount_earned - $taxOnProfit;
        $investmentAccount->increment('balance', $moneyEarned);
        $investmentAccount->save();
    }
}
