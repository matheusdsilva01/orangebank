<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class AccountFixedIncome extends Pivot
{
    use HasUuids;

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

    public $timestamps = false;

    public function fixedIncome(): BelongsTo
    {
        return $this->belongsTo(FixedIncome::class, 'fixed_income_id');
    }
}
