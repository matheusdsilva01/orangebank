<?php

namespace App\Dto;

use App\Models\Account\CurrentAccount;

final class DepositDTO
{
    public function __construct(
        public float $amount,
        public CurrentAccount $account,
    ) {}
}
