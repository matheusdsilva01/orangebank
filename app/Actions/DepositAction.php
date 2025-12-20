<?php

namespace App\Actions;

use App\Dto\DepositDTO;
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
    public function handle(DepositDTO $depositDTO): Transaction
    {
        if (! $depositDTO->account) {
            throw AccountException::accountNotFound();
        }

        if ($depositDTO->amount <= 0) {
            throw AccountException::invalidDepositAmount();
        }

        $depositDTO->account->increment('balance', $depositDTO->amount);
        $transaction = [
            'amount' => $depositDTO->amount,
            'type' => TransactionType::Deposit,
            'tax' => 0,
            'from_account_id' => null,
            'to_account_id' => $depositDTO->account->id,
        ];

        return $this->createTransactionAction->handle($transaction);
    }
}
