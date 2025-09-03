<?php

namespace App\Enums;

enum TransactionType: string
{
    case Internal = 'internal';
    case External = 'external';
    case Withdraw = 'withdraw';
    case Deposit = 'deposit';

    public function getLabel(): string
    {
        return match ($this) {
            self::Internal => 'Interna',
            self::External => 'Externa',
            self::Deposit => 'Depósito',
            self::Withdraw => 'Saque',
        };
    }
}
