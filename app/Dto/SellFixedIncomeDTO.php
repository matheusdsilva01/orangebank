<?php

namespace App\Dto;

use App\Models\Account\InvestmentAccount;
use App\Models\AccountFixedIncome;

final class SellFixedIncomeDTO
{
    public function __construct(
        public AccountFixedIncome $fixedIncomePurchased,
        public InvestmentAccount $account,
    ) {}
}

