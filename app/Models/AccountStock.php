<?php

namespace App\Models;

use App\Casts\MoneyCast;
use Brick\Money\Money;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Carbon;

/**
 * @property string $id
 * @property string $account_id
 * @property string $stock_id
 * @property int $quantity
 * @property Money $purchase_price
 * @property Money|null $sale_price
 * @property Carbon $purchase_date
 * @property Carbon|null $sale_date
 * @property Stock $stock
 */
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

    public function sell(): void
    {
        $this->sale_date = now();
        $this->save();
    }

    public $timestamps = false;

    /** @returns BelongsTo<Stock> */
    public function stock(): BelongsTo
    {
        return $this->belongsTo(Stock::class, 'stock_id');
    }

    protected $casts = [
        'purchase_price' => MoneyCast::class,
        'sale_price' => MoneyCast::class,
        'purchase_date' => 'datetime',
        'sale_date' => 'datetime',
    ];
}
