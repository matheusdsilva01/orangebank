<?php

namespace App\Repositories;

use App\Exceptions\AccountException;
use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Builder;

class TransactionRepository
{
    private Builder $query;

    public function __construct()
    {
        $this->query = Account::query();
    }
    public function withdraw(int $accountId, int $amount): void
    {
        $account = Account::query()->findOrFail($accountId);

        if ($account->balance < $amount) {
            throw AccountException::insufficientBalance();
        }

        $account->decrement('balance', $amount);
    }

    public function deposit(int $accountId, int $amount): void
    {
        $account = Account::query()->findOrFail($accountId);
        $account->increment('balance', $amount);
    }

    public function createTransaction(array $payload): Transaction
    {
        return Transaction::query()->create($payload);
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
    public function internalTransfer($fromAccount, $toAccount, int $amount): Transaction
    {
        if ($fromAccount->id == $toAccount->id) {
            throw AccountException::cannotTransferToSelfAccount();
        }

        if ($fromAccount->type == $toAccount->type) {
            throw AccountException::cannotTransferBetweenSameTypeAccounts();
        }

        if ($fromAccount->balance < $amount) {
            throw AccountException::insufficientBalance();
        }

        $this->withdraw($fromAccount->id, $amount);
        $this->deposit($toAccount->id, $amount);

        return $this->createTransaction([
            'from_account_id' => $fromAccount->id,
            'to_account_id' => $toAccount->id,
            'amount' => $amount,
            'type' => 'internal',
            'tax' => 0, // Assuming no tax for internal transfers
        ]);
    }

    /**
     * @throws AccountException
     */
    public function externalTransfer($fromAccount, $toAccount, int $amount): Transaction
    {
        $tax = 5;
        $amountDiscount = $amount + ($tax / 100);

        if ($fromAccount->type !== 'current' && $toAccount->type !== 'current') {
            throw AccountException::onlyTransferBetweenCurrentAccountsFromDifferentUsers();
        }

        if ($fromAccount->balance < $amountDiscount) {
            throw AccountException::insufficientBalance();
        }

        $fromAccount->balance -= $amountDiscount;
        $toAccount->balance += $amount;
        $fromAccount->save();
        $toAccount->save();

        return $this->createTransaction([
            'from_account_id' => $fromAccount->id,
            'to_account_id' => $toAccount->id,
            'amount' => $amount,
            'type' => 'external',
            'tax' => $tax
        ]);
    }
}
