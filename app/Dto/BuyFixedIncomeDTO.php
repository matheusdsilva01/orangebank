<?php

namespace App\Dto;

use App\Models\Account\InvestmentAccount;
use App\Models\FixedIncome;
use Brick\Money\Money;

class BuyFixedIncomeDTO
{
    public function __construct(
        public FixedIncome $fixedIncome,
        public Money $amount,
        public InvestmentAccount $account,
    ) {}
}
