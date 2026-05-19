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

        $amountEarnedDiscounted = $fixedIncomePurchased->amount_earned;

        if ($totalYield->isGreaterThan(0)) {
            $taxAmount = $totalYield->multipliedBy($tax, RoundingMode::HALF_EVEN);
            $amountEarnedDiscounted = $fixedIncomePurchased->amount_earned->minus($taxAmount);
        }

        $investmentAccount->credit($amountEarnedDiscounted);

        $fixedIncomePurchased->sale_date = now();
        $fixedIncomePurchased->save();
    }
}
