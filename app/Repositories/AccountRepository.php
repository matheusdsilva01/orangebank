<?php

namespace App\Repositories;

use App\Enums\AccountType;
use App\Exceptions\AccountException;
use App\Models\Account;
use Illuminate\Database\Eloquent\Builder;

class AccountRepository
{
    private Builder $query;

    public function __construct()
    {
        $this->query = Account::query();
    }

    /**
     * @throws AccountException
     */
    public function withdraw(string $accountNumber, float $amount): void
    {
        $account = $this->query->where('number', $accountNumber)->first();
        if ($account->type === AccountType::Investment) {
            throw AccountException::cannotWithdrawFromInvestmentAccount();
        }
        if ($account->balance < $amount) {
            throw AccountException::insufficientBalance();
        }
        $account->decrement('balance', $amount);
    }

    /**
     * @throws AccountException
     */
    public function deposit(string $accountNumber, mixed $amount): void
    {
        $account = $this->query->where('number', $accountNumber)->first();
        if ($account->type === AccountType::Investment) {
            throw AccountException::cannotDepositToInvestmentAccount();
        }
        $account->increment('balance', $amount);
    }
}
