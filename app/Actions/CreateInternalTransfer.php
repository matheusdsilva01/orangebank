<?php

namespace App\Actions;

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
        $amount = $attributes->amount;

        if ($fromAccount->id === $toAccount->id) {
            throw AccountException::cannotTransferToSelfAccount();
        }

        if ($fromAccount::class === $toAccount::class) {
            throw AccountException::cannotTransferBetweenSameTypeAccounts();
        }

        if ($fromAccount->balance < $amount) {
            throw AccountException::insufficientBalance();
        }

        $fromAccount->debit($amount);
        $toAccount->credit($amount);

        $transaction = [
            'from_account_id' => $fromAccount->id,
            'to_account_id' => $toAccount->id,
            'amount' => $amount,
            'type' => TransactionType::Internal,
            'tax' => 0,
        ];

        return $this->createTransactionAction->handle($transaction);
    }
}
