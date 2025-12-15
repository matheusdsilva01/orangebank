<?php

namespace App\Actions;

use App\Exceptions\AccountException;

class BuyFixedIncomeAction
{
    /**
     * @throws AccountException
     */
    public function handle(array $attributes): void
    {
        $stock = $attributes['stock'];
        $account = $attributes['account'];
        $amount = $attributes['amount'];

        if (! $account) {
            throw AccountException::cannotBuyStockWithoutAnInvestmentAccount();
        }
        if ($account->balance < $attributes['amount']) {
            throw AccountException::insufficientBalance();
        }

        $account->fixedIncomes()->attach(
            $stock->id,
            [
                'amount_investment' => $amount,
                'amount_earned' => $amount,
                'purchased_date' => now(),
            ]
        );
        $account->decrement('balance', $amount);
        $account->save();
    }
}
