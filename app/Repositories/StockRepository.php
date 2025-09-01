<?php

namespace App\Repositories;

use App\Enums\AccountType;
use App\Exceptions\AccountException;
use App\Models\Account\Account;
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

    public function getStockById(string $stockId)
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
    public function buyToAccount(string $accountId, string $stockId, int $qtd): void
    {
        $account = Account::query()->findOrFail($accountId);
        $stock = $this->query->findOrFail($stockId);
        $amount = $stock->current_price * $qtd;

        if ($account->type !== AccountType::Investment) {
            throw AccountException::cannotBuyStockWithoutAnInvestmentAccount();
        }
        if ($account->balance < $amount) {
            throw AccountException::insufficientBalance();
        }
        $account->stocks()->attach($stockId, ['quantity' => $qtd, 'current_price' => $stock->current_price]);
    }
}
