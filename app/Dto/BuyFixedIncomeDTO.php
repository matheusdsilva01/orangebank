<?php

namespace App\Dto;

use App\Models\Account\InvestmentAccount;
use App\Models\FixedIncome;
use App\Support\MoneyHelper;
use Brick\Money\Money;

final readonly class BuyFixedIncomeDTO
{
    public Money $amount;

    public function __construct(
        public FixedIncome $fixedIncome,
        int|float|string|Money $amount,
        public InvestmentAccount $account,
    ) {
        $this->amount = $amount instanceof Money ? $amount : MoneyHelper::of($amount);
    }
}
