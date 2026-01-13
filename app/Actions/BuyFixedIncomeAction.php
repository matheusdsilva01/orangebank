<?php

namespace App\Actions;

use App\Dto\BuyFixedIncomeDTO;
use App\Exceptions\AccountException;

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

        if (! $account) {
            throw AccountException::cannotBuyStockWithoutAnInvestmentAccount();
        }
        if ($account->balance->isLessThan($amount)) {
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
        $account->debit($amount);
    }
}
