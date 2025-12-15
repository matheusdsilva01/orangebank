<?php

namespace App\Http\Controllers;

use App\Actions\BuyStockAction;
use App\Actions\SellStockAction;
use App\Http\Requests\BuyStockRequest;
use App\Models\AccountStock;
use App\Models\Stock;
use IcehouseVentures\LaravelChartjs\Facades\Chartjs;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function detail(string $id)
    {
        $stock = Stock::findOrFail($id);
        $stockHistory = Stock::findOrFail($id)->histories;
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

    public function buy(BuyStockRequest $request, Stock $stock, BuyStockAction $buyStockAction)
    {
        $params = $request->validated();
        try {
            $payload = [
                'account' => auth()->user()->investmentAccount,
                'stock' => $stock,
                'quantity' => $params['quantity'],
            ];
            $buyStockAction->handle($payload);

            return redirect()->route('my-assets', ['type' => 'stocks']);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function index()
    {
        return Stock::query()->paginate();
    }

    public function sell(AccountStock $accountStock, SellStockAction $sellStockAction)
    {
        $payload = [
            'accountStock' => $accountStock,
        ];
        try {
            $sellStockAction->handle($payload);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->route('my-assets', ['type' => 'stocks']);
    }
}
