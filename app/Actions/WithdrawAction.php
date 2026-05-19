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
        $money = MoneyHelper::of($withdrawDTO->amount);

        if ($withdrawDTO->account->balance->isLessThan($money)) {
            throw AccountException::insufficientBalance();
        }

        $withdrawDTO->account->debit($money);

        return $this->createTransactionAction->handle(new CreateTransactionDTO(
            fromAccountId: $withdrawDTO->account->id,
            toAccountId: null,
            amount: (string) $money->getUnscaledAmount(),
            type: TransactionType::Withdraw
        ));
    }
}
