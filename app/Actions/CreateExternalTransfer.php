<?php

namespace App\Actions;

use App\Enums\TransactionType;
use App\Exceptions\AccountException;
use App\Models\Transaction;

class CreateExternalTransfer
{
    public function __construct(
        private CreateTransaction $createTransaction
    ) {}

    /**
     * @throws AccountException
     */
    public function handle(array $attributes): Transaction
    {
        $fromAccount = $attributes['fromAccount'];
        $toAccount = $attributes['toAccount'];
        $amount = $attributes['amount'];
        $tax = 50;

        if (! $toAccount) {
            throw AccountException::accountNotFound();
        }
        $amountDiscount = $amount + ($tax / 100);

        if ($fromAccount->balance < $amountDiscount) {
            throw AccountException::insufficientBalance();
        }

        $fromAccount->decrement('balance', $amountDiscount);
        $toAccount->increment('balance', $amount);

        return $this->createTransaction->handle([
            'from_account_id' => $fromAccount->id,
            'to_account_id' => $toAccount->id,
            'amount' => $amount,
            'type' => TransactionType::External,
            'tax' => $tax,
        ]);
    }
}
