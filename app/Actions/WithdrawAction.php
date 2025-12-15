<?php

namespace App\Actions;

use App\Enums\TransactionType;
use App\Exceptions\AccountException;
use App\Models\Transaction;

class WithdrawAction
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

        if ($account->balance < $amount) {
            throw AccountException::insufficientBalance();
        }

        $account->decrement('balance', $amount);
        $transaction = [
            'amount' => $amount,
            'type' => TransactionType::Withdraw,
            'tax' => 0,
            'from_account_id' => $account->id,
            'to_account_id' => null,
        ];

        return $this->createTransactionAction->handle($transaction);
    }
}
