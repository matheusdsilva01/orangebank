<?php

namespace App\Dto;

use App\Models\Account\Account;

final class DepositDTO
{
    public function __construct(
        public float $amount,
        public Account $account,
    ) {}
}
