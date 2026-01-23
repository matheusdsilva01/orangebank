<?php

namespace App\Actions;

use App\Dto\CreateTransactionDTO;
use App\Dto\WithdrawDTO;
use App\Enums\TransactionType;
use App\Exceptions\AccountException;
use App\Models\Transaction;
use App\Support\MoneyHelper;

class WithdrawAction
{
    public function __construct(
        private CreateTransactionAction $createTransactionAction
    ) {}

    /**
     * @throws AccountException
     */
    public function handle(WithdrawDTO $withdrawDTO): Transaction
    {
        if ($withdrawDTO->account->balance->isLessThan($withdrawDTO->amount)) {
            throw AccountException::insufficientBalance();
        }

        $withdrawDTO->account->withdraw($withdrawDTO->amount);

        return $this->createTransactionAction->handle(new CreateTransactionDTO(
            fromAccountId: $withdrawDTO->account->id,
            toAccountId: null,
            amount: (string) MoneyHelper::of($withdrawDTO->amount)->getUnscaledAmount(),
            type: TransactionType::Withdraw
        ));
    }
}
