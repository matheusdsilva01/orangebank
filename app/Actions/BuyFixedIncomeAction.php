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
        $stock = $attributes->stock;
        $account = $attributes->account;
        $amount = $attributes->amount;

        if ($account->balance->isLessThan($amount)) {
            throw AccountException::insufficientBalance();
        }

        $account->fixedIncomes()->attach(
            $stock->id,
            [
                'amount_investment' => (string) MoneyHelper::of($amount)->getUnscaledAmount(),
                'amount_earned' => (string) MoneyHelper::of($amount)->getUnscaledAmount(),
                'purchased_date' => now(),
            ]
        );
        $account->debit($amount);
    }
}
