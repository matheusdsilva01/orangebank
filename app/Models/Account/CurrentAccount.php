<?php

namespace App\Models\Account;

use App\Enums\AccountType;

class CurrentAccount extends Account
{
    public static function boot(): void
    {
        parent::boot();
        static::creating(function ($model) {
            $model->forceFill(['type' => AccountType::Current]);
        });
    }

    public static function booted(): void
    {
        static::addGlobalScope('account', function ($builder) {
            $builder->where('type', AccountType::Current);
        });
    }
}
