<?php

namespace App\Actions;

use App\Dto\CreateTransactionDTO;
use App\Dto\WithdrawDTO;
use App\Enums\TransactionType;
use App\Exceptions\AccountException;
use App\Models\Transaction;
use Brick\Money\Exception\MoneyMismatchException;

class WithdrawAction
{
    public function __construct(
        private CreateTransactionAction $createTransactionAction
    ) {}

    /**
     * @throws AccountException
     * @throws MoneyMismatchException
     */
    public function handle(WithdrawDTO $withdrawDTO): Transaction
    {
        if ($withdrawDTO->amount->isLessThanOrEqualTo(0)) {
            throw AccountException::invalidWithdrawAmount();
        }

        if ($withdrawDTO->account->balance->isLessThan($withdrawDTO->amount)) {
            throw AccountException::insufficientBalance();
        }

        $withdrawDTO->account->debit($withdrawDTO->amount);

        return $this->createTransactionAction->handle(new CreateTransactionDTO(
            fromAccountId: $withdrawDTO->account->id,
            toAccountId: null,
            amount: $withdrawDTO->amount,
            type: TransactionType::Withdraw
        ));
    }
}
