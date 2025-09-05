<?php

namespace App\Http\Controllers;

use App\Models\Stock;
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

    public function detail(string $id)
    {
        $stock = Stock::find($id);
        return view('stock-detail', compact('stock'));
    }
}
