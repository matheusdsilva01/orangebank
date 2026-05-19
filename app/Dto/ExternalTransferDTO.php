<?php

namespace App\Dto;

use App\Models\Account\CurrentAccount;
use App\Support\MoneyHelper;
use Brick\Money\Money;

final readonly class ExternalTransferDTO
{
    public Money $amount;

    public function __construct(
        int|float|string|Money $amount,
        public CurrentAccount $fromAccount,
        public CurrentAccount $toAccount,
    ) {
        $this->amount = $amount instanceof Money ? $amount : MoneyHelper::of($amount);
    }
}
