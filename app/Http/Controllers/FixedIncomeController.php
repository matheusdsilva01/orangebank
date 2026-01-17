<?php

namespace App\Http\Controllers;

use App\Actions\BuyFixedIncomeAction;
use App\Actions\SellFixedIncomeAction;
use App\Dto\BuyFixedIncomeDTO;
use App\Http\Requests\BuyFixedIncomeRequest;
use App\Models\AccountFixedIncome;
use App\Models\FixedIncome;

class FixedIncomeController extends Controller
{
    public function detail(FixedIncome $fixedIncome)
    {
        $investmentAccount = auth()->user()->investmentAccount;

        return view('fixed_income.detail', compact('fixedIncome', 'investmentAccount'));
    }

    public function buy(BuyFixedIncomeRequest $request, BuyFixedIncomeAction $buyFixedIncomeAction)
    {
        $params = $request->validated();
        $fixedIncome = $request->route('id');

        try {
            $account = auth()->user()->investmentAccount;

            $payload = new BuyFixedIncomeDTO(FixedIncome::findOrFail($fixedIncome), $params['amount'], $account);
            $buyFixedIncomeAction->handle($payload);

            return redirect()->route('my-assets', ['type' => 'fixed_income']);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function sell(AccountFixedIncome $accountFixedIncome, SellFixedIncomeAction $sellFixedIncomeAction)
    {
        try {
            $sellFixedIncomeAction->handle($accountFixedIncome);

            return redirect()->route('my-assets', ['type' => 'fixed_income']);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

    }
}
