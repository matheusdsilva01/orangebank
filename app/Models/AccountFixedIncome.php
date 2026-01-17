<?php

namespace App\Models;

use App\Casts\MoneyCast;
use App\Models\Account\InvestmentAccount;
use Brick\Money\Money;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @property Money $amount_investment
 * @property Money $amount_earned
 * @property InvestmentAccount $account
 */
class AccountFixedIncome extends Pivot
{
    use HasFactory, HasUuids;

    protected $table = 'account_fixed_income';

    protected $fillable = [
        'id',
        'account_id',
        'fixed_income_id',
        'amount_investment',
        'amount_earned',
        'purchased_date',
        'sale_date',
    ];

    protected $casts = [
        'amount_investment' => MoneyCast::class,
        'amount_earned' => MoneyCast::class,
    ];

    public $timestamps = false;

    public function fixedIncome(): BelongsTo
    {
        return $this->belongsTo(FixedIncome::class, 'fixed_income_id');
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(InvestmentAccount::class, 'account_id');
    }
}
