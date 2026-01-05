<?php

namespace App\Dto;

use App\Models\Account\CurrentAccount;

class ExternalTransferDTO
{
    public function __construct(
        public float $amount,
        public CurrentAccount $fromAccount,
        public CurrentAccount $toAccount,
    ) {}
}
