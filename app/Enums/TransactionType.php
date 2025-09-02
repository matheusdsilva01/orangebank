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

    public function getIcon(): string
    {
        return match ($this) {
            self::Internal => 'heroicon-o-arrows-right-left',
            self::External, self::Withdraw => 'heroicon-o-arrow-up-tray',
            self::Deposit => 'heroicon-o-arrow-down-tray',
        };
    }
}
