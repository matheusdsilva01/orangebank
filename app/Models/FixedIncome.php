<?php

namespace App\Models;

use App\Enums\FixedIncomeRateType;
use App\Enums\FixedIncomeType;
use App\Interfaces\Investable;
use App\Models\Account\InvestmentAccount;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
            'maturity' => 'datetime',
        ];
    }

    public function accounts(): BelongsToMany
    {
        return $this->belongsToMany(InvestmentAccount::class, 'account_fixed_income', 'fixed_income_id', 'account_id')
            ->withPivot([
                'id',
                'amount_earned',
                'amount_investment',
            ]);
    }

    private function randomFactor(): float
    {
        $selicAnual = 0.11 + (mt_rand() / mt_getrandmax()) * 0.01;

        return pow(1 + $selicAnual, 1 / 365) - 1;
    }

    public function calculateVolatility(): float
    {
        $annualTax = (float) $this->rate;

        if ($this->rateType === FixedIncomeRateType::Pre) {
            $this->accounts()->each(function ($account) use ($annualTax): void {
                $currentGain = $account->pivot->amount_earned - $account->pivot->amount_investment;
                $expectedDailyIncome = ($account->pivot->amount_investment * $annualTax) / 365.0;
                // implement a way to calculate the exact days elapsed since the investment
                // was made(created_at column in pivot table)
                $daysElapsed = round($currentGain / $expectedDailyIncome) + 1;

                $amountEarned =
                    $account->pivot->amount_investment + ($expectedDailyIncome * $daysElapsed);
                $account->pivot->amount_earned = $amountEarned;
                $account->pivot->save();
            });

            return $annualTax / 365.0;
        } else {
            $randomVariation = $this->randomFactor();
            $dailyTax = 1.0 + $annualTax * (1.0 / 365);
            $totalDailyTax = $randomVariation + $dailyTax;
            $this->accounts()->each(function ($account) use ($totalDailyTax): void {
                $account->pivot->amount_earned *= $totalDailyTax;
                $account->pivot->save();
            });

            return $totalDailyTax;
        }
    }
}
