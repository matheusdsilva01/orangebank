<?php

namespace App\Models;

use App\Enums\AccountType;
use App\Interfaces\Investable;
use App\Models\Account\Account;
use Brick\Money\Money;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property-read AccountStock $pivot
 */
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

    /**
     * @return BelongsToMany<Account>
     */
    public function accounts(): BelongsToMany
    {
        return $this->belongsToMany(
            Account::class,
        )
            ->using(AccountStock::class)
            ->where('accounts.type', AccountType::Investment);
    }

    /**
     * @return HasMany<StockHistory>
     */
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
        };
        $novaVariacao = 1 + $novaVariacao;
        if ($sign > 0) {
            dump(Money::of($this->current_price, 'BRL')->getAmount()->toFloat());
            $newPrice = round($this->current_price * $novaVariacao, PHP_ROUND_HALF_EVEN);
        } else {
            $newPrice = round($this->current_price / $novaVariacao, PHP_ROUND_HALF_EVEN);
        }
        $this->update(['daily_variation' => $novaVariacao, 'current_price' => $newPrice]);

        $this->histories()->create(['daily_price' => $newPrice, 'daily_variation' => $novaVariacao * $sign]);

        return $novaVariacao * $sign;
    }
}
