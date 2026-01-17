<?php

namespace App\Strategies\FixedIncome;

use App\Interfaces\FixedIncomeCalculatorStrategy;
use App\Models\FixedIncome;
use Brick\Math\RoundingMode;

class PreFixedIncomeCalculator implements FixedIncomeCalculatorStrategy
{
    public function calculate(FixedIncome $fixedIncome): float
    {
        $accounts = $fixedIncome->accounts;
        $dailyRate = $fixedIncome->rate / 365;

        foreach ($accounts as $account) {
            $currentGain = $account->pivot->amount_earned->minus($account->pivot->amount_investment);
            $expectedDailyIncome = $account->pivot->amount_investment->multipliedBy($dailyRate, RoundingMode::HALF_EVEN);
            // Refactor that to use purchased_date difference
            $daysElapsed = $currentGain->isZero()
                ? 1
                : round($currentGain->getAmount()->toFloat() / $expectedDailyIncome->getAmount()->toFloat()) + 1;
            $totalGain = $account->pivot->amount_investment->multipliedBy($dailyRate * $daysElapsed, RoundingMode::HALF_EVEN);
            $amountEarned =
                $account->pivot->amount_investment->plus($totalGain, RoundingMode::HALF_EVEN);
            $account->pivot->amount_earned = $amountEarned;
            $account->pivot->save();
        }

        return $dailyRate;
    }
}
