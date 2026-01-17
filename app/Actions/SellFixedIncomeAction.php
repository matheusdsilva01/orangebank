<?php

namespace App\Actions;

use App\Models\AccountFixedIncome;
use Brick\Math\RoundingMode;

class SellFixedIncomeAction
{
    public function handle(AccountFixedIncome $fixedIncomePurchased): void
    {
        $tax = config('finance.tax_ir_fixed_income_profit'); // 22% tax on earnings

        $investmentAccount = $fixedIncomePurchased->account;
        $totalYield = $fixedIncomePurchased->amount_earned->minus($fixedIncomePurchased->amount_investment);
        $amountEarnedDiscounted = $totalYield->multipliedBy($tax, RoundingMode::HALF_EVEN);

        $investmentAccount->credit($fixedIncomePurchased->amount_earned->minus($amountEarnedDiscounted)->getAmount()->toFloat());

        $fixedIncomePurchased->sale_date = now();
        $fixedIncomePurchased->save();
    }
}
