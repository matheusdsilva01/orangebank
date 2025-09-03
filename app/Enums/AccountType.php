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
    public static function fromModel(string $model): AccountType
    {
        return match ($model) {
            CurrentAccount::class => AccountType::Current,
            InvestmentAccount::class => AccountType::Investment,
        };
    }

    public function getLabel(): string
    {
        return match ($this) {
            AccountType::Current => 'Conta Corrente',
            AccountType::Investment => 'Conta Investimento',
        };
    }
}
