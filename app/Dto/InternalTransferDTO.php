<?php

namespace App\Dto;

use App\Models\Account\Account;
use App\Support\MoneyHelper;
use Brick\Money\Money;

final readonly class InternalTransferDTO
{
    public Money $amount;

    public function __construct(
        int|float|string|Money $amount,
        public Account $fromAccount,
        public Account $toAccount,
    ) {
        $this->amount = $amount instanceof Money ? $amount : MoneyHelper::of($amount);
    }
}
