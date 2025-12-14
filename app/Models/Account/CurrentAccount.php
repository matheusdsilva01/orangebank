<?php

namespace App\Models\Account;

use App\Enums\AccountType;
use App\Enums\TransactionType;
use App\Exceptions\AccountException;
use App\Models\Transaction;

class CurrentAccount extends Account
{
    public static function boot(): void
    {
        parent::boot();
        static::creating(function ($model): void {
            $model->forceFill(['type' => AccountType::Current]);
        });
    }

    public static function booted(): void
    {
        static::addGlobalScope('account', function ($builder): void {
            $builder->where('type', AccountType::Current);
        });
    }
}
