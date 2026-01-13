<?php

namespace App\Actions;

use App\Dto\BuyStockDTO;
use App\Exceptions\AccountException;
use Carbon\Carbon;

class BuyStockAction
{
    /**
     * @throws AccountException
     */
    public function handle(BuyStockDTO $dto): void
    {
        $account = $dto->account;
        $stock = $dto->stock;
        $quantity = $dto->quantity;

        $amount = round($stock->current_price * $quantity, 2);

        if ($account->balance < $amount) {
            throw AccountException::insufficientBalance();
        }

        $account->stocks()->attach($stock->id, ['quantity' => $quantity, 'purchase_price' => $stock->current_price, 'purchase_date' => Carbon::now()]);
        $account->decrement('balance', $amount);
        $account->save();
    }
}
