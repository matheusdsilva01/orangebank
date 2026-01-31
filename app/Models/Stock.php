<?php

namespace App\Models;

use App\Casts\MoneyCast;
use App\Enums\AccountType;
use App\Interfaces\Investable;
use App\Models\Account\Account;
use Brick\Math\RoundingMode;
use Brick\Money\Money;
use Database\Factories\StockFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property-read AccountStock $pivot
 * @property Money $current_price
 * @property int $quantity
 */
class Stock extends Model implements Investable
{
    /** @use HasFactory<StockFactory> */
    use HasFactory, HasUuids;

    protected $guarded = ['id'];

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

    protected $casts = [
        'current_price' => MoneyCast::class,
    ];

    /**
     * @return BelongsToMany<Account, $this, AccountStock>
     */
    public function accounts(): BelongsToMany
    {
        return $this->belongsToMany(
            Account::class,
        )
            ->where('accounts.type', AccountType::Investment)
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

    /**
     * @return HasMany<StockHistory, $this>
     */
    public function histories(): HasMany
    {
        return $this->hasMany(StockHistory::class);
    }

    public function calculateVolatility(): float
    {
        $novaVariacao = $this->generateRandomVariation();
        $newPrice = $this->current_price->multipliedBy($novaVariacao, RoundingMode::HALF_EVEN);

        $this->update(['daily_variation' => $novaVariacao, 'current_price' => $newPrice]);

        $this->histories()->create(['daily_price' => $newPrice, 'daily_variation' => $novaVariacao]);

        return $novaVariacao;
    }

    private function generateRandomVariation(): float
    {
        $randomNumber = rand(1, 10);

        $sign = (mt_rand(0, 1) == 0) ? -1 : 1;
        $novaVariacao = (float) match (true) {
            $randomNumber <= 4 => rand(1, 20) / 1000,
            $randomNumber <= 7 => rand(2, 3) / 100,
            $randomNumber <= 9 => rand(3, 4) / 100,
            default => rand(4, 5) / 100,
        };

        return 1 + ($novaVariacao * $sign);
    }
}
