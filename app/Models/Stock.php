<?php

namespace App\Models;

use App\Interfaces\Investable;
use App\Models\Account\InvestmentAccount;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Stock extends Model implements Investable
{
    use HasFactory, HasUuids;

    protected $guarded = ['id'];

    protected $table = 'stocks';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'symbol',
        'sector',
        'current_price',
        'daily_variation',
    ];

    public function accounts(): BelongsToMany
    {
        return $this->belongsToMany(InvestmentAccount::class, 'account_id')
            ->withPivot([
                'quantity',
                'purchase_price',
                'sale_price',
                'purchase_date',
                'sale_date',
            ]);
    }

    public function histories(): HasMany
    {
        return $this->hasMany(StockHistory::class);
    }

    public function calculateVolatility(): float
    {
        $randomNumber = rand(1, 10);

        $sign = (mt_rand(0, 1) == 0) ? -1 : 1;
        $novaVariacao = (float) match (true) {
            $randomNumber <= 4 => rand(1, 20) / 1000,
            $randomNumber <= 7 => rand(2, 3) / 100,
            $randomNumber <= 9 => rand(3, 4) / 100,
            $randomNumber <= 10 => rand(4, 5) / 100,
        } * $sign;
        $newPrice = round($this->current_price * (1 + $novaVariacao), 2);
        $this->update(['daily_variation' => $novaVariacao * 100, 'current_price' => $newPrice]);

        $this->histories()->create(['daily_price' => $newPrice, 'daily_variation' => $novaVariacao * 100]);

        return $novaVariacao * 100;
    }
}
