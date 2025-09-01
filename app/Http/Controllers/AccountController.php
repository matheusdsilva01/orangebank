<?php

namespace App\Http\Controllers;

use App\Enums\TransactionType;
use App\Exceptions\AccountException;
use App\Http\Requests\AccountDepositRequest;
use App\Http\Requests\AccountWithdrawRequest;
use App\Models\Account\Account;
use App\Models\Transaction;
use App\Repositories\AccountRepository;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AccountController extends Controller
{
    private AccountRepository $repository;

    public function __construct(AccountRepository $repository)
    {
        $this->repository = $repository;
    }

    public function dashboard()
    {
        $currentAccount = auth()->user()->currentAccount()->get()->first();
        $investmentAccount = auth()->user()->investmentAccount()->get()->first();
        $transactions = $currentAccount->transactions()->union($investmentAccount->transactions())->get();
        return view('dashboard', compact('currentAccount', 'investmentAccount', 'transactions'));
    }

    public function transfer(Request $request)
    {
        $payload = $request->validate([
            'amount' => ['required', 'numeric:', 'min:1', ],
            'destination' => ['required', 'string', 'exists:accounts,number'],
        ]);
        $user = auth()->user();
        try {
            $user->currentAccount()->get()->first()->externalTransfer($payload);
        } catch (AccountException $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
        return redirect(route('dashboard'));
    }

    public function withdraw(AccountWithdrawRequest $request)
    {
        $payload = $request->validated();
        try {
            $this->repository->withdraw($payload['number'], $payload['amount']);
            $account = Account::query()->where('number', $payload['number'])->firstOrFail();
            $transaction = [
                'amount' => $payload['amount'],
                'type' => TransactionType::Withdraw,
                'tax' => 0,
                'from_account_id' => $account->id,
                'to_account_id' => null,
            ];
            $transaction = Transaction::query()->create($transaction);

            return response()->json($transaction, Response::HTTP_OK);
        } catch (AccountException $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
    }

    public function deposit(AccountDepositRequest $request)
    {
        $payload = $request->validated();
        try {
            $this->repository->deposit($payload['number'], $payload['amount']);
            $account = Account::query()->where('number', $payload['number'])->firstOrFail();
            $transaction = [
                'amount' => $payload['amount'],
                'type' => TransactionType::Deposit,
                'tax' => 0,
                'from_account_id' => null,
                'to_account_id' => $account->id,
            ];
            $transaction = Transaction::query()->create($transaction);

            return response()->json($transaction, Response::HTTP_OK);
        } catch (AccountException $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
    }

}
