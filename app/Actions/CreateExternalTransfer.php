<?php

namespace App\Actions;

use App\Dto\CreateTransactionDTO;
use App\Dto\ExternalTransferDTO;
use App\Enums\TransactionType;
use App\Exceptions\AccountException;
use App\Models\Transaction;
use App\Support\MoneyHelper;
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
        $moneyAmount = MoneyHelper::of($externalTransferDTO->amount);
        $tax = config('finance.tax_external_transfer');

        if ($toAccount->user_id === $fromAccount->user_id) {
            throw AccountException::cannotMakeExternalTransferToSameUser();
        }

        $totalTax = $moneyAmount->multipliedBy($tax, RoundingMode::HALF_EVEN);
        $totalDiscount = $moneyAmount->plus($totalTax);

        if ($fromAccount->balance->isLessThan($totalDiscount)) {
            throw AccountException::insufficientBalance();
        }

        $fromAccount->debit($totalDiscount);
        $toAccount->credit($moneyAmount);

        return $this->createTransactionAction->handle(new CreateTransactionDTO(
            $fromAccount->id,
            $toAccount->id,
            (string) $moneyAmount->getUnscaledAmount(),
            TransactionType::External,
            $tax,
        ));
    }
}
