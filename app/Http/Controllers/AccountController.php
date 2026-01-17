<?php

namespace App\Http\Controllers;

use App\Actions\CreateExternalTransfer;
use App\Actions\CreateInternalTransfer;
use App\Actions\DepositAction;
use App\Actions\WithdrawAction;
use App\Dto\DepositDTO;
use App\Dto\ExternalTransferDTO;
use App\Dto\InternalTransferDTO;
use App\Dto\WithdrawDTO;
use App\Exceptions\AccountException;
use App\Http\Requests\AccountDepositRequest;
use App\Http\Requests\AccountWithdrawRequest;
use App\Http\Requests\ExternalTransferRequest;
use App\Http\Requests\InternalTransferRequest;
use App\Models\Account\CurrentAccount;
use App\Models\Transaction;
use Illuminate\Http\Request;

class AccountController extends Controller
{
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

    public function externalTransfer(ExternalTransferRequest $request, CreateExternalTransfer $createExternalTransfer)
    {
        $payload = $request->validated();
        $user = auth()->user();
        $attributes = new ExternalTransferDTO(
            $payload['amount'],
            $user->currentAccount,
            CurrentAccount::query()->where('number', $payload['destination'])->first(),
        );

        try {
            $createExternalTransfer->handle($attributes);
        } catch (AccountException $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }

        return redirect(route('dashboard'));
    }

    public function internalTransfer(InternalTransferRequest $request, CreateInternalTransfer $createInternalTransfer)
    {
        $params = $request->validated();
        $user = auth()->user();

        try {
            if ($params['mode'] === 'current-investment') {
                $attributes = [
                    'fromAccount' => $user->currentAccount,
                    'toAccount' => $user->investmentAccount,
                    'amount' => $params['amount'],
                ];
            } else {
                $attributes = [
                    'fromAccount' => $user->investmentAccount,
                    'toAccount' => $user->currentAccount,
                    'amount' => $params['amount'],
                ];
            }

            $transferData = new InternalTransferDTO(
                $attributes['amount'],
                $attributes['fromAccount'],
                $attributes['toAccount'],
            );

            $createInternalTransfer->handle($transferData);
        } catch (AccountException $e) {
            return redirect()->back()->withErrors(['message' => $e->getMessage()]);
        }

        return redirect(route('dashboard'));
    }

    public function withdraw(AccountWithdrawRequest $request, WithdrawAction $withdrawAction)
    {
        $attributes = $request->validated();

        try {
            $user = auth()->user();
            $account = $user->currentAccount;

            if (! $account) {
                throw AccountException::accountNotFound();
            }

            $attributes = new WithdrawDTO(
                $attributes['amount'],
                $account
            );

            $withdrawAction->handle($attributes);

            return redirect(route('dashboard'));
        } catch (AccountException $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
    }

    public function deposit(AccountDepositRequest $request, DepositAction $depositAction)
    {
        $payload = $request->validated();

        try {
            $user = auth()->user();
            $account = $user->currentAccount;

            if (! $account) {
                throw new AccountException('Current account not found', 404);
            }

            $attributes = new DepositDTO(
                $payload['amount'],
                $account
            );
            $depositAction->handle($attributes);

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

    public function depositForm()
    {
        $currentAccount = auth()->user()->currentAccount;

        return view('deposit', compact('currentAccount'));
    }
}
