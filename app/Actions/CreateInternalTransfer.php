<?php

namespace App\Actions;

use App\Dto\CreateTransactionDTO;
use App\Dto\InternalTransferDTO;
use App\Enums\TransactionType;
use App\Exceptions\AccountException;
use App\Models\Transaction;

class CreateInternalTransfer
{
    public function __construct(
        private CreateTransactionAction $createTransactionAction
    ) {}

    /**
     * @throws AccountException
     */
    public function handle(InternalTransferDTO $attributes): Transaction
    {
        $fromAccount = $attributes->fromAccount;
        $toAccount = $attributes->toAccount;

        if ($fromAccount->id === $toAccount->id) {
            throw AccountException::cannotTransferToSelfAccount();
        }

        if ($fromAccount::class === $toAccount::class) {
            throw AccountException::cannotTransferBetweenSameTypeAccounts();
        }

        if ($fromAccount->balance->isLessThan($attributes->amount)) {
            throw AccountException::insufficientBalance();
        }

        $fromAccount->debit($attributes->amount);
        $toAccount->credit($attributes->amount);

        return $this->createTransactionAction->handle(new CreateTransactionDTO(
            fromAccountId: $fromAccount->id,
            toAccountId: $toAccount->id,
            amount: $attributes->amount,
            type: TransactionType::Internal
        ));
    }
}
