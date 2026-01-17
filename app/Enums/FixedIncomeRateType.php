<?php

namespace App\Enums;

use App\Interfaces\FixedIncomeCalculatorStrategy;
use App\Strategies\FixedIncome\PostFixedIncomeCalculator;
use App\Strategies\FixedIncome\PreFixedIncomeCalculator;

enum FixedIncomeRateType: string
{
    case Post = 'post';
    case Pre = 'pre';

    public function getLabel(): string
    {
        return match ($this) {
            FixedIncomeRateType::Post => 'Pós-fixado',
            FixedIncomeRateType::Pre => 'Pré-fixado',
        };
    }

    public function getYieldStrategy(): FixedIncomeCalculatorStrategy
    {
        return match ($this) {
            FixedIncomeRateType::Post => new PostFixedIncomeCalculator(),
            FixedIncomeRateType::Pre => new PreFixedIncomeCalculator(),
        };
    }

}
