<?php

namespace App\Actions;

use App\Dto\BuyStockDTO;
use App\Exceptions\AccountException;
use App\Models\AccountStock;
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

        AccountStock::create([
            'account_id' => $account->id,
            'stock_id' => $stock->id,
            'quantity' => $quantity,
            'purchase_price' => $stock->current_price,
            'purchase_date' => now(),
        ]);

        $account->debit($amount);
    }
}
