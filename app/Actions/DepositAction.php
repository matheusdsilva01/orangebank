<?php

namespace App\Actions;

use App\Enums\TransactionType;
use App\Exceptions\AccountException;
use App\Models\Transaction;

class DepositAction
{
    public function __construct(
        private CreateTransactionAction $createTransactionAction
    ) {}

    /**
     * @throws AccountException
     */
    public function handle(array $attributes): Transaction
    {
        $amount = $attributes['amount'];
        $account = $attributes['account'];

        if (! $account) {
            throw AccountException::accountNotFound();
        }

        if ($amount <= 0) {
            throw AccountException::invalidDepositAmount();
        }

        $account->increment('balance', $amount);
        $transaction = [
            'amount' => $amount,
            'type' => TransactionType::Deposit,
            'tax' => 0,
            'from_account_id' => null,
            'to_account_id' => $account->id,
        ];

        return $this->createTransactionAction->handle($transaction);
    }
}
