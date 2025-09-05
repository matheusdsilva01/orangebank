<?php

namespace App\Enums;

enum FixedIncomeType: string
{
    case CDB = 'cdb';
    case DirectTreasury = 'direct_treasury';

    public function getLabel(): string
    {
        return match ($this)
        {
            FixedIncomeType::CDB => 'CDB',
            FixedIncomeType::DirectTreasury => 'Tesouro Direto',
        };
    }
}
