<?php

namespace App\Actions;

use App\Dto\CreateTransactionDTO;
use App\Dto\ExternalTransferDTO;
use App\Enums\TransactionType;
use App\Exceptions\AccountException;
use App\Models\Transaction;
use Brick\Math\RoundingMode;

class CreateExternalTransfer
{
    public function __construct(
        private CreateTransactionAction $createTransactionAction
    ) {}

    /**
     * @throws AccountException
     */
    public function handle(ExternalTransferDTO $externalTransferDTO): Transaction
    {
        $fromAccount = $externalTransferDTO->fromAccount;
        $toAccount = $externalTransferDTO->toAccount;
        $tax = config('finance.tax_external_transfer');

        if ($toAccount->user_id === $fromAccount->user_id) {
            throw AccountException::cannotMakeExternalTransferToSameUser();
        }

        $totalTax = $externalTransferDTO->amount->multipliedBy($tax, RoundingMode::HALF_EVEN);
        $totalDiscount = $externalTransferDTO->amount->plus($totalTax);

        if ($fromAccount->balance->isLessThan($totalDiscount)) {
            throw AccountException::insufficientBalance();
        }

        $fromAccount->debit($totalDiscount);
        $toAccount->credit($externalTransferDTO->amount);

        return $this->createTransactionAction->handle(new CreateTransactionDTO(
            fromAccountId: $fromAccount->id,
            toAccountId: $toAccount->id,
            amount: $externalTransferDTO->amount,
            type: TransactionType::External,
            tax: $tax,
        ));
    }
}
