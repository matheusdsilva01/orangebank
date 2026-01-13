<?php

namespace App\Actions;

use App\Dto\CreateTransactionDTO;
use App\Models\Transaction;

class CreateTransactionAction
{
    public function handle(CreateTransactionDTO $attributes): Transaction
    {
        return Transaction::create($attributes->toArray());
    }
}
