<?php

namespace App\Dto;

use App\Models\Account\Account;

class InternalTransferDTO
{
    public function __construct(
        public float $amount,
        public Account $fromAccount,
        public Account $toAccount,
    ) {}
}
