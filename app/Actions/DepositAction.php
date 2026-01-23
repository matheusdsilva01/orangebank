<?php

namespace App\Actions;

use App\Dto\CreateTransactionDTO;
use App\Dto\DepositDTO;
use App\Enums\TransactionType;
use App\Exceptions\AccountException;
use App\Models\Transaction;
use App\Support\MoneyHelper;

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
        if ($depositDTO->amount <= 0) {
            throw AccountException::invalidDepositAmount();
        }

        $depositDTO->account->deposit($depositDTO->amount);

        return $this->createTransactionAction->handle(new CreateTransactionDTO(
            fromAccountId: null,
            toAccountId: $depositDTO->account->id,
            amount: (string) MoneyHelper::of($depositDTO->amount)->getUnscaledAmount(),
            type: TransactionType::Deposit
        ));
    }
}
