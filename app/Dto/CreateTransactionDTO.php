<?php

namespace App\Dto;

use App\Enums\TransactionType;

final class CreateTransactionDTO
{
    public function __construct(
        public ?string $fromAccountId,
        public ?string $toAccountId,
        public string $amount,
        public TransactionType $type,
        public ?float $tax = 0,
    ) {}

    public function toArray(): array
    {
        return [
            'from_account_id' => $this->fromAccountId,
            'to_account_id' => $this->toAccountId,
            'amount' => $this->amount,
            'type' => $this->type,
            'tax' => $this->tax,
        ];
    }
}
