<?php

namespace App\Actions;

use App\Dto\ExternalTransferDTO;
use App\Enums\TransactionType;
use App\Exceptions\AccountException;
use App\Models\Transaction;

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
        $amount = $externalTransferDTO->amount;
        $tax = config('finance.tax_external_transfer');

        if (! $toAccount) {
            throw AccountException::accountNotFound();
        }
        $amountDiscount = $amount + $tax;

        if ($fromAccount->balance < $amountDiscount) {
            throw AccountException::insufficientBalance();
        }

        $fromAccount->withdraw($amountDiscount);
        $toAccount->deposit($amount);

        return $this->createTransactionAction->handle([
            'from_account_id' => $fromAccount->id,
            'to_account_id' => $toAccount->id,
            'amount' => $amount,
            'type' => TransactionType::External,
            'tax' => $tax,
        ]);
    }
}
