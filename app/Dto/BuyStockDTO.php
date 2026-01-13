<?php

namespace App\Dto;

use App\Models\Account\InvestmentAccount;
use App\Models\Stock;

final class BuyStockDTO
{
    public function __construct(
        public Stock $stock,
        public int $quantity,
        public InvestmentAccount $account,
    ) {}
}
