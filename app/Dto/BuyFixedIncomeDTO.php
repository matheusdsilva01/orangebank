<?php

namespace App\Dto;

use App\Models\Account\InvestmentAccount;
use App\Models\FixedIncome;

class BuyFixedIncomeDTO
{
    public function __construct(
        public FixedIncome $stock,
        public float $amount,
        public InvestmentAccount $account,
    ) {}
}
