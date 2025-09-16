<?php

namespace App\Enums;

enum FixedIncomeRateType: string
{
    case Post = 'post';
    case Pre = 'pre';

    public function getLabel(): string
    {
        return match ($this) {
            FixedIncomeRateType::Post => 'Pós-fixado',
            FixedIncomeRateType::Pre => 'Pré-fixado',
        };
    }
}
