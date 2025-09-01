<?php

namespace App\Enums;

use App\Models\Account\CurrentAccount;
use App\Models\Account\InvestmentAccount;

enum AccountType: string
{
    case Current = 'current';
    case Investment = 'investment';

    public function getModel(): string
    {
        return match ($this) {
            AccountType::Current => CurrentAccount::class,
            AccountType::Investment => InvestmentAccount::class,
        };
    }
}
