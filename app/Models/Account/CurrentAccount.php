<?php

namespace App\Models\Account;

use App\Enums\AccountType;

class CurrentAccount extends Account
{
    public function deposit(float $amount): void
    {
        $this->increment('balance', $amount);
    }

    public function withdraw(float $amount): void
    {
        if ($amount > $this->balance) {
            throw new \InvalidArgumentException('Insufficient funds for withdrawal.');
        }

        $this->decrement('balance', $amount);
    }

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
