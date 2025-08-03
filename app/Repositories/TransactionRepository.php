<?php

namespace App\Repositories;

use App\Enums\AccountType;
use App\Exceptions\AccountException;
use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Builder;

class TransactionRepository
{
    private Builder $query;

    public function __construct()
    {
        $this->query = Transaction::query();
    }

    public function createTransaction(Transaction $payload): Transaction
    {
        return $this->query->create($payload->toArray());
    }

    /**
     * @throws AccountException
     */
    public function createTransfer(array $payload): Transaction
    {
        $fromAccount = Account::query()->findOrFail($payload['fromAccountId']);
        $toAccount = Account::query()->findOrFail($payload['toAccountId']);
        if ($fromAccount->user->id === $toAccount->user->id) {
            return $this->internalTransfer(
                $fromAccount,
                $toAccount,
                $payload['amount']
            );
        } else {
            return $this->externalTransfer(
                $fromAccount,
                $toAccount,
                $payload['amount']
            );
        }
    }

    /**
     * @throws AccountException
     */
    public function internalTransfer(Account $fromAccount, Account $toAccount, float $amount): Transaction
    {
        if ($fromAccount->id === $toAccount->id) {
            throw AccountException::cannotTransferToSelfAccount();
        }
        if ($fromAccount->type === $toAccount->type) {
            throw AccountException::cannotTransferBetweenSameTypeAccounts();
        }

        if ($fromAccount->balance < $amount) {
            throw AccountException::insufficientBalance();
        }

        $fromAccount->decrement('balance', $amount);
        $toAccount->increment('balance', $amount);

        $transaction = new Transaction([
            'from_account_id' => $fromAccount->id,
            'to_account_id' => $toAccount->id,
            'amount' => $amount,
            'type' => 'internal',
            'tax' => 0, // Assuming no tax for internal transfers
        ]);

        return $this->createTransaction($transaction);
    }

    /**
     * @throws AccountException
     */
    public function externalTransfer(Account $fromAccount, Account $toAccount, float $amount): Transaction
    {
        $tax = 50;
        $amountDiscount = $amount + ($tax / 100);
        // Transactions between users can only be made between current accounts
        if ($fromAccount->type !== AccountType::Current && $toAccount->type !== AccountType::Current) {
            throw AccountException::onlyTransferBetweenCurrentAccountsFromDifferentUsers();
        }

        if ($fromAccount->balance < $amountDiscount) {
            throw AccountException::insufficientBalance();
        }

        $fromAccount->decrement('balance', $amountDiscount);
        $toAccount->increment('balance', $amount);

        $transaction = new Transaction([
            'from_account_id' => $fromAccount->id,
            'to_account_id' => $toAccount->id,
            'amount' => $amount,
            'type' => 'external',
            'tax' => $tax,
        ]);

        return $this->createTransaction($transaction);
    }
}
