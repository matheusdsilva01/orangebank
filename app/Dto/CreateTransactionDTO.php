<?php

namespace App\Dto;

use App\Enums\TransactionType;
use App\Support\MoneyHelper;
use Brick\Money\Money;

final readonly class CreateTransactionDTO
{
    public Money $amount;

    public function __construct(
        public ?string $fromAccountId,
        public ?string $toAccountId,
        int|float|string|Money $amount,
        public TransactionType $type,
        public ?float $tax = 0,
    ) {
        $this->amount = $amount instanceof Money ? $amount : MoneyHelper::of($amount);
    }

    /**
     * Convert the DTO to an associative array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'from_account_id' => $this->fromAccountId,
            'to_account_id' => $this->toAccountId,
            'amount' => (string) $this->amount->getUnscaledAmount(),
            'type' => $this->type,
            'tax' => $this->tax,
        ];
    }
}
