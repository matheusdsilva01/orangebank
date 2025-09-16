<?php

namespace App\Http\Controllers;

use App\Models\FixedIncome;
use App\Models\Stock;
use Illuminate\Http\Request;

class AssetController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->query('type', 'stocks');
        if (! in_array($type, ['stocks', 'fixed_income'])) {
            return redirect()->route('assets', ['type' => 'stocks']);
        }
        $stocks = Stock::all();
        $fixedIncomes = FixedIncome::all();
        $investmentAccount = auth()->user()->investmentAccount;

        return view('assets.index', compact('stocks', 'type', 'fixedIncomes', 'investmentAccount'));
    }

    public function myAssets(Request $request)
    {
        $investmentAccount = auth()->user()->investmentAccount;
        $stocks = $investmentAccount->stocks()->wherePivotNull('sale_date')->get();
        $fixedIncomes = $investmentAccount->fixedIncomes;
        $type = $request->query('type', 'stocks');

        if (! in_array($type, ['stocks', 'fixed_income'])) {
            return redirect()->route('my-assets', ['type' => 'stocks']);
        }

        return view('assets.my-assets', compact('stocks', 'fixedIncomes', 'investmentAccount', 'type'));
    }
}
