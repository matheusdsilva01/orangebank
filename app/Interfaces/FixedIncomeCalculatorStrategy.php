<?php

namespace App\Interfaces;

use App\Models\FixedIncome;

interface FixedIncomeCalculatorStrategy
{
    public function calculate(FixedIncome $fixedIncome): float;
}
