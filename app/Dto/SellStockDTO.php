<?php

namespace App\Dto;

use App\Models\AccountStock;
use App\Models\Stock;

final class SellStockDTO
{
    public function __construct(
        public AccountStock $accountStock,
        public Stock $stock,
    ) {}
}

