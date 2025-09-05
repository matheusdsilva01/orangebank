<?php

namespace App\Repositories;

use App\Exceptions\AccountException;
use App\Models\Account\Account;
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
    public function withdraw(float $amount): void
    {
        $user = auth()->user();
        $account = $user->currentAccount;
        if (! $account) {
            throw AccountException::accountNotFound();
        }
        if ($account->balance < $amount) {
            throw AccountException::insufficientBalance();
        }
        $account->decrement('balance', $amount);
    }

    /**
     * @throws AccountException
     */
    public function deposit(float $amount): void
    {
        $account = auth()->user()->currentAccount;
        if (! $account) {
            throw AccountException::accountNotFound();
        }
        $account->increment('balance', $amount);
    }
}
