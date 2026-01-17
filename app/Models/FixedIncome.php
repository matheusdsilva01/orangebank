<?php

namespace App\Models;

use App\Casts\MoneyCast;
use App\Enums\FixedIncomeRateType;
use App\Enums\FixedIncomeType;
use App\Interfaces\Investable;
use App\Models\Account\InvestmentAccount;
use Brick\Math\RoundingMode;
use Brick\Money\Money;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property Money $minimumInvestment
 */
class FixedIncome extends Model implements Investable
{
    use HasFactory, HasUuids;

    protected $guarded = ['id'];

    protected $fillable = [
        'name',
        'type',
        'rate',
        'rateType',
        'maturity',
        'minimumInvestment',
    ];

    protected function casts(): array
    {
        return [
            'type' => FixedIncomeType::class,
            'rateType' => FixedIncomeRateType::class,
            'minimumInvestment' => MoneyCast::class,
            'maturity' => 'datetime',
        ];
    }

    public function accounts(): BelongsToMany
    {
        return $this
            ->belongsToMany(InvestmentAccount::class, 'account_fixed_income', 'fixed_income_id', 'account_id')
            ->using(AccountFixedIncome::class)
            ->withPivot(['id', 'amount_investment', 'amount_earned', 'purchased_date', 'sale_date']);
    }

    public function calculateVolatility(): float
    {
        return $this->rateType->getYieldStrategy()->calculate($this);
    }
}
