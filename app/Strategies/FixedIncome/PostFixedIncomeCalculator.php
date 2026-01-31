<?php

namespace App\Strategies\FixedIncome;

use App\Interfaces\FixedIncomeCalculatorStrategy;
use App\Models\FixedIncome;
use Brick\Math\RoundingMode;

class PostFixedIncomeCalculator implements FixedIncomeCalculatorStrategy
{
    public function calculate(FixedIncome $fixedIncome): float
    {
        $randomVariation = $this->randomFactor();
        $annualTax = $fixedIncome->rate;
        $dailyTax = 1.0 + $annualTax * (1.0 / 365);
        $totalDailyTax = $randomVariation + $dailyTax;
        $accounts = $fixedIncome->accounts;

        foreach ($accounts as $account) {
            $account->pivot->amount_earned = $account->pivot->amount_earned->multipliedBy($totalDailyTax, RoundingMode::HALF_EVEN);
            $account->pivot->save();
        }

        return $totalDailyTax;
    }

    private function randomFactor(): float
    {
        $selicAnual = 0.11 + (mt_rand() / mt_getrandmax()) * 0.01;

        return pow(1 + $selicAnual, 1 / 365) - 1;
    }
}
