<?php

namespace app\Repositories;

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

    public function createTransaction(array $payload): Transaction
    {
        return Transaction::query()->create($payload);
    }

    /**
     * @throws AccountException
     */
    public function internalTranfer($fromAccountId, $toAccountId, int $amount): Transaction
    {
        $fromAccount = $this->query->findOrFail($fromAccountId);
        $toAccount = $this->query->findOrFail($toAccountId);
        if ($fromAccount->id === $toAccount->id) {
            throw AccountException::cannotTransferToSelfAccount();
        }

        if ($fromAccount->type === $toAccount->type) {
            throw AccountException::cannotTransferBetweenSameTypeAccounts();
        }

        if ($fromAccount->balance < $amount) {
            throw AccountException::insufficientBalance();
        }

        $fromAccount->balance -= $amount;
        $toAccount->balance += $amount;
        $fromAccount->save();
        $toAccount->save();

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
    public function externalTranfer(int $amount, $fromAccountId, $toAccountId): Transaction
    {
        $fromAccount = $this->query->findOrFail($fromAccountId);
        $toAccount = $this->query->findOrFail($toAccountId);
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
