<?php

namespace App\Actions;

use App\Dto\WithdrawDTO;
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
    public function handle(WithdrawDTO $withdrawDTO): Transaction
    {
        if (! $withdrawDTO->account) {
            throw AccountException::accountNotFound();
        }

        if ($withdrawDTO->account->balance < $withdrawDTO->amount) {
            throw AccountException::insufficientBalance();
        }

        $withdrawDTO->account->withdraw($withdrawDTO->amount);
        $transaction = [
            'amount' => $withdrawDTO->amount,
            'type' => TransactionType::Withdraw,
            'tax' => 0,
            'from_account_id' => $withdrawDTO->account->id,
            'to_account_id' => null,
        ];

        return $this->createTransactionAction->handle($transaction);
    }
}
