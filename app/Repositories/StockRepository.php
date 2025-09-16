<?php

namespace App\Repositories;

use App\Exceptions\AccountException;
use App\Models\Stock;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class StockRepository
{
    private Builder $query;

    public function __construct()
    {
        $this->query = Stock::query();
    }

    public function getStockById(string $stockId): Stock
    {
        return $this->query->findOrFail($stockId);
    }

    public function paginate(): LengthAwarePaginator
    {
        return $this->query->paginate();
    }

    /**
     * @throws AccountException
     */
    public function buyToAccount(string $id, int $quantity): void
    {
        $account = auth()->user()->investmentAccount;
        $stock = $this->query->findOrFail($id);
        $amount = round($stock->current_price * $quantity, 2);

        if (! $account) {
            throw AccountException::cannotBuyStockWithoutAnInvestmentAccount();
        }
        if ($account->balance < $amount) {
            throw AccountException::insufficientBalance();
        }
        $account->stocks()->attach($id, ['quantity' => $quantity, 'purchase_price' => $stock->current_price, 'purchase_date' => now()]);
        $account->decrement('balance', $amount);
    }

    public function getStockHistory(string $id)
    {
        return $this->query->findOrFail($id)->histories;
    }
}
