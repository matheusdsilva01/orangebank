<?php

namespace App\Http\Controllers;

use App\Models\AccountStock;
use App\Repositories\StockRepository;
use IcehouseVentures\LaravelChartjs\Facades\Chartjs;
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

            return redirect()->route('my-assets', ['type' => 'stocks']);
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
        $stockHistory = $this->stockRepository->getStockHistory($id);
        $data = $stockHistory->pluck('daily_price');
        $labels = $stockHistory->pluck('created_at')->map(fn ($item) => $item->format('d/m/Y'))->toArray();
        $chart = Chartjs::build()
            ->name('line_chart_test')
            ->type('line')
            ->size(['width' => 400, 'height' => 200])
            ->labels($labels)
            ->datasets([
                [
                    'label' => 'Preço Diário',
                    'backgroundColor' => 'rgba(219, 39, 119, 0.31)',
                    'borderColor' => 'rgba(219, 39, 119, 0.7)',
                    'pointBorderColor' => 'rgba(219, 39, 119, 0.7)',
                    'pointBackgroundColor' => 'rgba(219, 39, 119, 0.7)',
                    'pointHoverBackgroundColor' => '#fff',
                    'pointHoverBorderColor' => 'rgba(220,220,220,1)',
                    'data' => $data,
                ],
            ])
            ->options([
                'responsive' => true,
                'plugins' => [
                    'legend' => [
                        'display' => true,
                        'position' => 'top',
                    ],
                ],
                'scales' => [
                    'x' => [
                        'title' => [
                            'display' => true,
                            'text' => 'Data',
                        ],
                    ],
                    'y' => [
                        'title' => [
                            'display' => true,
                            'text' => 'Preço(R$)',
                        ],
                    ],
                ],
            ]);

        return view('stock-detail', compact('stock', 'stockHistory', 'chart'));
    }

    public function detailPurchased(Request $request)
    {
        $stockPurchaseDetail = AccountStock::query()->findOrFail($request->route('id'));

        $stock = $stockPurchaseDetail->stock;
        $user = auth()->user();
        $investmentAccount = $user->investmentAccount;

        $currentValue = $stock->current_price * $stockPurchaseDetail->quantity;
        $investedValue = $stockPurchaseDetail->purchase_price * $stockPurchaseDetail->quantity;
        $profitLoss = $currentValue - $investedValue;
        $profitLossPercentage = ($profitLoss / $investedValue) * 100;
        $stockSalePriceDiscount = $profitLoss > 0
            ? ($profitLoss * $stockPurchaseDetail->quantity) * 0.22
            : $stock->current_price * $stockPurchaseDetail->quantity;
        $stockHistory = $stock->histories()->orderBy('created_at')->get();
        $data = $stockHistory->pluck('daily_price');
        $labels = $stockHistory->pluck('created_at')->map(fn ($item) => $item->format('d/m/Y'))->toArray();

        $chart = Chartjs::build()
            ->name('stock_performance_chart')
            ->type('line')
            ->size(['width' => 400, 'height' => 200])
            ->labels($labels)
            ->datasets([['label' => 'Preço Diário',
                'backgroundColor' => 'rgba(217, 70, 239, 0.1)',
                'borderColor' => 'rgba(217, 70, 239, 1)',
                'pointBorderColor' => 'rgba(217, 70, 239, 1)',
                'pointBackgroundColor' => 'rgba(217, 70, 239, 1)',
                'pointHoverBackgroundColor' => '#fff',
                'pointHoverBorderColor' => 'rgba(217, 70, 239, 1)',
                'data' => $data,
                'fill' => true, ], ])
            ->options(['responsive' => true,
                'plugins' => ['legend' => ['display' => true,
                    'position' => 'top', ], ],
                'scales' => ['x' => ['title' => ['display' => true,
                    'text' => 'Data']],
                    'y' => ['title' => ['display' => true,
                        'text' => 'Preço (R$)']]]]);

        return view('stock-detail-purchased', compact('stock', 'user', 'investmentAccount', 'stockPurchaseDetail', 'currentValue', 'investedValue', 'profitLoss', 'profitLossPercentage', 'stockSalePriceDiscount', 'chart'));
    }

    public function sell(Request $request)
    {
        $id = $request->route('id');
        $purchase = AccountStock::query()->findOrFail($id);
        $stock = $purchase->stock;
        $profitLoss = $stock->current_price - $purchase->purchase_price;
        $account = auth()->user()->investmentAccount;

        if ($profitLoss > 0) {
            // IR 15% sobre o lucro
            $tax = ($profitLoss * $purchase->quantity) * 0.15;

            $salePrice = ($stock->current_price * $purchase->quantity) - $tax;
            $account->balance += $salePrice * $purchase->quantity;
        } else {
            $salePrice = $stock->current_price * $purchase->quantity;
            $account->balance += $salePrice;
        }

        $purchase->sale_price = $salePrice;
        $purchase->sale_date = now();
        $account->save();
        $purchase->save();

        return redirect()->route('my-assets', ['type' => 'stocks']);
    }
}
