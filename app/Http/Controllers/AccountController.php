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
        $accountIds = auth()->user()->accounts()->pluck('id');

        $transactions = Transaction::where(function ($query) use ($accountIds) {
            $query->whereIn('from_account_id', $accountIds)
                ->orWhereIn('to_account_id', $accountIds);
        })->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact('currentAccount', 'investmentAccount', 'transactions'));
    }

    public function transferForm(Request $request)
    {
        $type = $request->query('type', 'external');
        return view('transfer', compact('type'));
    }

    public function transfer(Request $request)
    {
        $payload = $request->validate([
            'amount' => ['required', 'numeric:', 'min:1'],
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

    public function internalTransfer(Request $request)
    {
        $params = $request->validate([
            'amount' => ['required', 'numeric:', 'min:1'],
            'mode' => ['required', 'in:current-investment,investment-current'],
        ]);

        $payload = [
            'amount' => $params['amount'],
            'origin' => explode('-', $params['mode'])[0],
            'destination' => explode('-', $params['mode'])[1],
        ];

        $user = auth()->user();

        try {
            if ($payload['origin'] === 'current') {
                $user->currentAccount()->get()->first()->internalTransfer($payload);
            } else {
                $user->investmentAccount()->get()->first()->internalTransfer($payload);
            }
        } catch (AccountException $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }

        return redirect(route('dashboard'));
    }

    public function withdraw(AccountWithdrawRequest $request)
    {
        $payload = $request->validated();
        try {
            $this->repository->withdraw($payload['amount']);
            $user = auth()->user();
            $account = $user->currentAccount;
            $transaction = [
                'amount' => $payload['amount'],
                'type' => TransactionType::Withdraw,
                'tax' => 0,
                'from_account_id' => $account->id,
                'to_account_id' => null,
            ];
            $transaction = Transaction::query()->create($transaction);

            return redirect(route('dashboard'));
        } catch (AccountException $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
    }

    public function withdrawForm()
    {
        $currentAccount = auth()->user()->currentAccount;
        return view('withdraw', compact('currentAccount'));
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
