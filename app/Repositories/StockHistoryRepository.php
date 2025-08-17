<?php

namespace App\Repositories;

use App\Models\StockHistory;
use Illuminate\Database\Eloquent\Builder;

class StockHistoryRepository
{
    private Builder $query;

    public function __construct()
    {
        $this->query = StockHistory::query();
    }

    public function getHistoryStockByStockId(string $stockId)
    {
        return $this->query->where('stock_id', $stockId);
    }
}
