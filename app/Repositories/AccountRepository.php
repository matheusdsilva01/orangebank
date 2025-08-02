<?php

namespace App\Repositories;

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
        $account = $this->query->whereColumn($accountNumber, 'number')->first();
        if ($account->type === 'investment') {
            throw AccountException::cannotWithdrawFromInvestmentAccount();
        }
        if ($account->balance < $amount) {
            throw AccountException::insufficientBalance();
        }
        $account->decrement('balance', $amount);
    }
}
