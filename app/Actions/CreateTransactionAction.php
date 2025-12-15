<?php

namespace App\Actions;

use App\Models\Transaction;

class CreateTransactionAction
{
    public function handle(array $data): Transaction
    {
        return Transaction::create($data);
    }
}
