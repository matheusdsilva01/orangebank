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

    /**
     * @throws AccountException
     */
    public function externalTransfer(array $payload): Transaction
    {
        $tax = 50;
        $amount = $payload['amount'];
        $number = $payload['destination'];
        $toAccount = CurrentAccount::query()->where('number', $number)->get()->first();
        if (!$toAccount) {
            throw AccountException::accountNotFound();
        }
        $amountDiscount = $amount + ($tax / 100);

        if ($this->balance < $amountDiscount) {
            throw AccountException::insufficientBalance();
        }

        $this->decrement('balance', $amountDiscount);
        $toAccount->increment('balance', $amount);

        $transaction = Transaction::create([
            'from_account_id' => $this->id,
            'to_account_id' => $toAccount->id,
            'amount' => $amount,
            'type' => TransactionType::External,
            'tax' => $tax,
        ]);

        return $transaction;
    }

}
