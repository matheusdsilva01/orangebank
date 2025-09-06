<?php

namespace App\Http\Controllers;

use App\Exceptions\AccountException;
use App\Models\FixedIncome;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class FixedIncomeController extends Controller
{
    private Builder $query;

    public function __construct()
    {
        $this->query = FixedIncome::query();
    }

    public function detail($id)
    {
        $fixedIncome = FixedIncome::findOrFail($id);
        $investmentAccount = auth()->user()->investmentAccount;

        return view('fixed_income.detail', compact('fixedIncome', 'investmentAccount'));
    }

    public function buy(Request $request)
    {
        $params = $request->validate([
            'amount' => 'required|integer|min:1',
        ]);
        $payload = [
            'id' => $request->route('id'),
            'amount' => $params['amount'],
        ];
        try {
            $account = auth()->user()->investmentAccount;

            if (!$account) {
                throw AccountException::cannotBuyStockWithoutAnInvestmentAccount();
            }
            if ($account->balance < $payload['amount']) {
                throw AccountException::insufficientBalance();
            }
            $account->fixedIncomes()->attach($payload['id'], ['value' => $payload['amount']]);
            $account->decrement('balance', $payload['amount']);
            return redirect()->route('dashboard');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
