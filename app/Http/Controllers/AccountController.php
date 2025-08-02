<?php

namespace App\Http\Controllers;

use App\Exceptions\AccountException;
use App\Http\Requests\AccountWithdrawRequest;
use App\Models\Account;
use App\Models\Transaction;
use App\Repositories\AccountRepository;
use Symfony\Component\HttpFoundation\Response;

class AccountController extends Controller
{
    private AccountRepository $repository;

    public function __construct(AccountRepository $repository)
    {
        $this->repository = $repository;
    }

    public function withdraw(AccountWithdrawRequest $request)
    {
        $payload = $request->validated();
        try {
            $this->repository->withdraw($payload['number'], $payload['amount']);
            $account = Account::query()->where('number', $payload['number'])->firstOrFail();
            $transaction = [
                'amount' => $payload['amount'],
                'type' => 'withdraw',
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
}
