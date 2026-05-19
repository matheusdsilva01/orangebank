<?php

namespace App\Actions;

use App\Dto\CreateTransactionDTO;
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
        if ($depositDTO->amount->isLessThanOrEqualTo(0)) {
            throw AccountException::invalidDepositAmount();
        }

        $depositDTO->account->credit($depositDTO->amount);

        return $this->createTransactionAction->handle(new CreateTransactionDTO(
            fromAccountId: null,
            toAccountId: $depositDTO->account->id,
            amount: $depositDTO->amount,
            type: TransactionType::Deposit
        ));
    }
}
