<?php

namespace App\Models\Account;

use App\Enums\AccountType;

class InvestmentAccount extends Account
{
    public static function boot(): void
    {
        parent::boot();
        static::creating(function ($model) {
            $model->forceFill(['type' => AccountType::Investment]);
        });
    }

    public static function booted(): void
    {
        static::addGlobalScope('investment', function ($builder) {
            $builder->where('type', AccountType::Investment);
        });
    }
}
