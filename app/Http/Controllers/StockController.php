<?php

namespace App\Http\Controllers;

use App\Repositories\StockRepository;
use Illuminate\Http\Request;

class StockController extends Controller
{
    private StockRepository $stockRepository;

    public function __construct(StockRepository $stockRepository)
    {
        $this->stockRepository = $stockRepository;
    }

    public function buy(Request $request)
    {
        $params = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);
        $payload = [
            'id' => $request->route('id'),
            'quantity' => $params['quantity'],
        ];
        try {
            $this->stockRepository->buyToAccount($payload['id'], $payload['quantity']);
            return redirect()->route('dashboard');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function index()
    {
        return $this->stockRepository->paginate();
    }

    public function detail(string $id)
    {
        $stock = $this->stockRepository->getStockById($id);
        return view('stock-detail', compact('stock'));
    }
}
