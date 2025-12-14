<?php

namespace App\Http\Controllers;

use App\Actions\CreateExternalTransfer;
use App\Actions\CreateInternalTransfer;
use App\Enums\TransactionType;
use App\Exceptions\AccountException;
use App\Http\Requests\AccountDepositRequest;
use App\Http\Requests\AccountWithdrawRequest;
use App\Models\Account\CurrentAccount;
use App\Models\Transaction;
use App\Repositories\AccountRepository;
use App\Actions\CreateTransaction;
use Illuminate\Http\Request;

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

        $transactions = Transaction::where(function ($query) use ($accountIds): void {
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

    public function externalTransfer(Request $request, CreateExternalTransfer $createExternalTransfer)
    {
        $payload = $request->validate([
            'amount' => ['required', 'numeric:', 'min:1'],
            'destination' => ['required', 'string', 'exists:accounts,number'],
        ]);
        $user = auth()->user();

        try {
            $createExternalTransfer->handle([
                'fromAccount' => $user->currentAccount,
                'toAccount' => CurrentAccount::query()->where('number', $payload['destination'])->first(),
                'amount' => $payload['amount']
            ]);
        } catch (AccountException $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }

        return redirect(route('dashboard'));
    }

    public function internalTransfer(Request $request, CreateInternalTransfer $createInternalTransfer)
    {
        $params = $request->validate([
            'amount' => ['required', 'numeric:', 'min:1'],
            'mode' => ['required', 'in:current-investment,investment-current'],
        ]);
        $user = auth()->user();

        try {
            if ($params['mode'] === 'current-investment') {
                $attributes = [
                    'fromAccount' => $user->currentAccount,
                    'toAccount' => $user->investmentAccount,
                    'amount' => $params['amount']
                ];
            } else {
                $attributes = [
                    'fromAccount' => $user->investmentAccount,
                    'toAccount' => $user->currentAccount,
                    'amount' => $params['amount']
                ];
            }
            $createInternalTransfer->handle($attributes);
        } catch (AccountException $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }

        return redirect(route('dashboard'));
    }

    public function withdraw(AccountWithdrawRequest $request, CreateTransaction $createTransaction)
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
            $createTransaction->handle($transaction);

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

    public function deposit(AccountDepositRequest $request, CreateTransaction $createTransaction)
    {
        $payload = $request->validated();
        try {
            $this->repository->deposit($payload['amount']);
            $account = auth()->user()->currentAccount;
            $transaction = [
                'amount' => $payload['amount'],
                'type' => TransactionType::Deposit,
                'tax' => 0,
                'from_account_id' => null,
                'to_account_id' => $account->id,
            ];
            $createTransaction->handle($transaction);

            return redirect(route('dashboard'));
        } catch (AccountException $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
    }

    public function depositForm()
    {
        $currentAccount = auth()->user()->currentAccount;

        return view('deposit', compact('currentAccount'));
    }
}
