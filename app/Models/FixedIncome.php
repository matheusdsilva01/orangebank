<?php

namespace App\Models;

use App\Interfaces\Investable;
use App\Models\Account\Account;
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
            'maturity' => 'datetime',
        ];
    }

    public function accounts(): BelongsToMany
    {
        return $this->belongsToMany(Account::class);
    }

    public function calculateVolatility(): float
    {
        $annualTax = (float) $this->rate;

        $dailyTax = pow(1.0 + $annualTax, (1.0 / 365.0)) - 1.0;
        $valorAtual = $this->price * (1 + $dailyTax);
        $this->accounts()->each(function ($account) {
            dump($account);
        });

        return $dailyTax;
    }
}
/*
 *  investi 1000 no CDB001
 *
 */
