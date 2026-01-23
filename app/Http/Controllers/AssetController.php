<?php

namespace App\Http\Controllers;

use App\Models\FixedIncome;
use App\Models\Stock;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AssetController extends Controller
{
    public function index(Request $request): RedirectResponse|View
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

    public function myAssets(Request $request): RedirectResponse|View
    {
        $investmentAccount = auth()->user()->investmentAccount;
        $stocks = $investmentAccount->stocks()->wherePivotNull('sale_date')->get();
        $fixedIncomes = $investmentAccount->fixedIncomes()->wherePivotNull('sale_date')->get();
        $type = $request->query('type', 'stocks');

        if (! in_array($type, ['stocks', 'fixed_income'])) {
            return redirect()->route('my-assets', ['type' => 'stocks']);
        }

        return view('assets.my-assets', compact('stocks', 'fixedIncomes', 'investmentAccount', 'type'));
    }
}
