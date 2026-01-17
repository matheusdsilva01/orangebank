<?php

namespace App\Actions;

use App\Dto\BuyStockDTO;
use App\Exceptions\AccountException;
use Brick\Math\RoundingMode;

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

        $amount = $stock->current_price->multipliedBy($quantity, RoundingMode::HALF_EVEN);

        if ($account->balance->isLessThan($amount)) {
            throw AccountException::insufficientBalance();
        }

        $account->stocks()->attach($stock->id, [
            'quantity' => $quantity,
            'purchase_price' => (string) $stock->current_price->getUnscaledAmount(),
            'purchase_date' => now(),
        ]);
        $account->debit($amount->getAmount()->toFloat());
        $account->save();
    }
}
