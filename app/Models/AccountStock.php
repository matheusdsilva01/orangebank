<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class AccountStock extends Pivot
{
    use HasUuids;

    protected $table = 'account_stock';

    protected $fillable = [
        'id',
        'account_id',
        'stock_id',
        'quantity',
        'purchase_price',
        'sale_price',
        'purchase_date',
        'sale_date',
    ];

    public $timestamps = false;

    /** @returns BelongsTo<Stock> */
    public function stock(): BelongsTo
    {
        return $this->belongsTo(Stock::class, 'stock_id');
    }
}
