<?php

namespace App\Http\Controllers;

use App\Repositories\StockHistoryRepository;

class StockHistoryController extends Controller
{
    private StockHistoryRepository $stockHistoryRepository;

    public function index(StockHistoryRepository $stockHistoryRepository)
    {
        $this->stockHistoryRepository = $stockHistoryRepository;
    }

    public function stockHistoryById(string $stockId)
    {
        return $this->stockHistoryRepository->getHistoryStockByStockId($stockId);
    }
}
