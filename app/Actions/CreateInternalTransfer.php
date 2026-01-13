<?php

namespace App\Actions;

use App\Dto\CreateTransactionDTO;
use App\Dto\InternalTransferDTO;
use App\Enums\TransactionType;
use App\Exceptions\AccountException;
use App\Models\Transaction;
use App\Support\MoneyHelper;

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
        $amount = $attributes->amount;

        if ($fromAccount->id === $toAccount->id) {
            throw AccountException::cannotTransferToSelfAccount();
        }

        if ($fromAccount::class === $toAccount::class) {
            throw AccountException::cannotTransferBetweenSameTypeAccounts();
        }

        if ($fromAccount->balance->isLessThan(MoneyHelper::of($amount))) {
            throw AccountException::insufficientBalance();
        }

        $fromAccount->debit($amount);
        $toAccount->credit($amount);

        return $this->createTransactionAction->handle(new CreateTransactionDTO(
            $fromAccount->id,
            $toAccount->id,
            (string) MoneyHelper::of($amount)->getUnscaledAmount(),
            TransactionType::Internal
        ));
    }
}
