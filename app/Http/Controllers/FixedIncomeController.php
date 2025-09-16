<?php

namespace App\Http\Controllers;

use App\Exceptions\AccountException;
use App\Models\AccountFixedIncome;
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

            if (! $account) {
                throw AccountException::cannotBuyStockWithoutAnInvestmentAccount();
            }
            if ($account->balance < $payload['amount']) {
                throw AccountException::insufficientBalance();
            }
            $account->fixedIncomes()->attach($payload['id'], ['amount_investment' => $payload['amount'], 'amount_earned' => $payload['amount'], 'purchased_date' => now()]);
            $account->decrement('balance', $payload['amount']);
            return redirect()->route('my-assets',['type' => 'fixed_income']);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function sell(string $id)
    {
        try {
            $fixedIncomePurchased = AccountFixedIncome::query()->findOrFail($id);
            // 22% IR on profit
            $value = ($fixedIncomePurchased->amount_earned - $fixedIncomePurchased->amount_investment) * 0.22;
            // truncate to 4 decimal places
            $taxOnProfit = floor($value * 10000) / 10000;
            $fixedIncomePurchased->sale_date = now();
            $fixedIncomePurchased->save();
            $investmentAccount = auth()->user()->investmentAccount;
            $investmentAccount->increment('balance', $fixedIncomePurchased->amount_earned - $taxOnProfit);
            return redirect()->route('my-assets', ['type' => 'fixed_income']);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

    }
}
