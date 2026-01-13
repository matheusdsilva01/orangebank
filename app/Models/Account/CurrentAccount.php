<?php

namespace App\Models\Account;

use App\Enums\AccountType;

class CurrentAccount extends Account
{
    public function deposit(float $amount): void
    {
        $this->balance = $this->balance->plus($amount);
        $this->save();
    }

    public function withdraw(float $amount): void
    {
        if ($this->balance->isLessThan($amount)) {
            throw new \InvalidArgumentException('Insufficient funds for withdrawal.');
        }

        $this->balance = $this->balance->minus($amount);
        $this->save();
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
