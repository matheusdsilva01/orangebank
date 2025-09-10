<?php

namespace App\Http\Controllers;

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
        $stockHistory = $this->stockRepository->getStockHistory($id);
        $data = $stockHistory->pluck('daily_price');
        $labels = $stockHistory->pluck('created_at')->map(fn($item) => $item->format('d/m/Y'))->toArray();
        $chart = Chartjs::build()
            ->name('line_chart_test')
            ->type('line')
            ->size(['width' => 400, 'height' => 200])
            ->labels($labels)
            ->datasets([
                [
                    "label" => "Preço Diário",
                    'backgroundColor' => "rgba(219, 39, 119, 0.31)",
                    'borderColor' => "rgba(219, 39, 119, 0.7)",
                    "pointBorderColor" => "rgba(219, 39, 119, 0.7)",
                    "pointBackgroundColor" => "rgba(219, 39, 119, 0.7)",
                    "pointHoverBackgroundColor" => "#fff",
                    "pointHoverBorderColor" => "rgba(220,220,220,1)",
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
                            'text' => 'Data'
                        ]
                    ],
                    'y' => [
                        'title' => [
                            'display' => true,
                            'text' => 'Preço(R$)'
                        ]
                    ]
                ]
            ]);
        return view('stock-detail', compact('stock', 'stockHistory', 'chart'));
    }
}
