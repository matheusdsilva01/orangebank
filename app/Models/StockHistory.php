<?php

namespace App\Models;

use App\Casts\MoneyCast;
use Brick\Money\Money;
use Database\Factories\StockHistoryFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property Money $daily_price
 */
class StockHistory extends Model
{
    /** @use HasFactory<StockHistoryFactory> */
    use HasFactory, HasUuids;

    protected $fillable = [
        'uuid',
        'daily_variation',
        'daily_price',
        'stock_id',
    ];

    protected $casts = [
        'daily_price' => MoneyCast::class,
    ];

    /**
     * @return BelongsTo<Stock, $this>
     */
    public function stock(): BelongsTo
    {
        return $this->belongsTo(Stock::class);
    }

    protected function casts(): array
    {
        return [
            'created_at' => 'custom_datetime',
        ];
    }
}
