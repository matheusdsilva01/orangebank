<?php

namespace App\Http\Controllers;

use App\Repositories\StockRepository;

class StockController extends Controller
{
    private StockRepository $stockRepository;

    public function __construct(StockRepository $stockRepository)
    {
        $this->stockRepository = $stockRepository;
    }

    public function buy(string $accountId, string $stockId, int $qtd)
    {
        return $this->stockRepository->buyToAccount($accountId, $stockId, $qtd);
    }

    public function index()
    {
        return $this->stockRepository->paginate();
    }

    public function show(string $id)
    {
        return $this->stockRepository->getStockById($id);
    }
}
