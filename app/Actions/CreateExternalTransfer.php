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
        $amount = $externalTransferDTO->amount;
        $tax = config('finance.tax_external_transfer');

        if (! $toAccount) {
            throw AccountException::accountNotFound();
        }

        if ($toAccount->user_id === $fromAccount->user_id) {
            throw AccountException::cannotMakeExternalTransferToSameUser();
        }

        if ($fromAccount->balance->isLessThan(MoneyHelper::of($amount))) {
            throw AccountException::insufficientBalance();
        }

        $totalTax = MoneyHelper::of($amount)->multipliedBy($tax, RoundingMode::HALF_EVEN);
        $totalDiscount = MoneyHelper::of($amount)->plus($totalTax);

        $fromAccount->withdraw($totalDiscount->getAmount()->toFloat());
        $toAccount->deposit($amount);

        return $this->createTransactionAction->handle(new CreateTransactionDTO(
            $fromAccount->id,
            $toAccount->id,
            (string) MoneyHelper::of($amount)->getUnscaledAmount(),
            TransactionType::External,
            $tax,
        ));
    }
}
