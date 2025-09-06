<?php

namespace App\Models\Account;

use App\Enums\AccountType;
use App\Models\FixedIncome;
use App\Models\Stock;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class InvestmentAccount extends Account
{
    public static function boot(): void
    {
        parent::boot();
        static::creating(function ($model): void {
            $model->forceFill(['type' => AccountType::Investment]);
        });
    }

    public static function booted(): void
    {
        static::addGlobalScope('investment', function ($builder): void {
            $builder->where('type', AccountType::Investment);
        });
    }

    public function fixedIncomes(): BelongsToMany
    {
        return $this->belongsToMany(FixedIncome::class, 'account_fixed_income', 'account_id')
//            ->using(new class extends Pivot{
//                use HasUuids;
//            })
            ->withPivot([
                'value',
            ])->withTimestamps();
    }

    public function stocks(): BelongsToMany
    {
        return $this->belongsToMany(Stock::class, 'account_stock', 'account_id')
            ->withPivot([
                'quantity',
                'purchase_price',
                'sale_price',
                'purchase_date',
                'sale_date',
            ]);
    }
}
