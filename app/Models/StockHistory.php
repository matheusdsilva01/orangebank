<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockHistory extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'uuid',
        'daily_variation',
        'daily_price',
        'stock_id',
    ];

    public function stock(): BelongsTo
    {
        return $this->belongsTo(Stock::class);
    }

    protected function casts(): array
    {
        return [
            'created_at' => 'custom_datetime'
        ];
    }
}
