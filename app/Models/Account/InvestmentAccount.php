<?php

namespace App\Models\Account;

use App\Enums\AccountType;
use App\Models\AccountFixedIncome;
use App\Models\AccountStock;
use App\Models\FixedIncome;
use App\Models\Stock;
use Database\Factories\Account\InvestmentAccountFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property AccountFixedIncome $pivot
 *
 * @use HasFactory<InvestmentAccountFactory>
 */
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

    /**
     * @return BelongsToMany<FixedIncome, $this, AccountFixedIncome, 'pivot'>
     */
    public function fixedIncomes(): BelongsToMany
    {
        return $this->belongsToMany(FixedIncome::class, 'account_fixed_income', 'account_id')
            ->using(AccountFixedIncome::class)
            ->withPivot(['id', 'amount_investment', 'amount_earned', 'purchased_date', 'sale_date']);
    }

    /**
     * @return BelongsToMany<Stock, $this, AccountStock, 'pivot'>
     */
    public function stocks(): BelongsToMany
    {
        return $this->belongsToMany(Stock::class, 'account_stock', 'account_id')
            ->using(AccountStock::class)
            ->withPivot([
                'id',
                'quantity',
                'purchase_price',
                'sale_price',
                'purchase_date',
                'sale_date',
            ]);
    }
}
