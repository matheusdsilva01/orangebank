<?php

namespace App\Actions;

use App\Dto\BuyFixedIncomeDTO;
use App\Exceptions\AccountException;
use App\Support\MoneyHelper;

class BuyFixedIncomeAction
{
    /**
     * @throws AccountException
     */
    public function handle(BuyFixedIncomeDTO $attributes): void
    {
        $fixedIncome = $attributes->fixedIncome;
        $account = $attributes->account;
        $amount = $attributes->amount;

        if ($account->balance->isLessThan($amount)) {
            throw AccountException::insufficientBalance();
        }

        \App\Models\AccountFixedIncome::create([
            'account_id' => $account->id,
            'fixed_income_id' => $fixedIncome->id,
            'amount_investment' => $amount,
            'amount_earned' => $amount,
            'purchased_date' => now(),
        ]);

        $account->debit($amount);
    }
}
