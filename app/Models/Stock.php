<?php

namespace App\Models;

use App\Interfaces\Investable;
use App\Models\Account\Account;
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
        // Converte a variação diária para decimal (ex: 1.2 -> 0.012)
        $vies = $this->daily_variation / 100;
        // Adiciona um fator aleatório pequeno (ex: entre -0.5% e 0.5%)
        $fatorAleatorio = (float) rand(-5, 5) / 1000; // +/- 0.5%
        // Calcula a variação total do dia, somando o viés e o fator aleatório
        $novaVariacao = round($vies + $fatorAleatorio, 4);

        $newPrice = round($this->current_price * (1 + $novaVariacao), 2);
        $this->update(['daily_variation' => $novaVariacao * 100, 'current_price' => $newPrice]);

        $this->histories()->create(['daily_price' => $newPrice, 'daily_variation' => $novaVariacao * 100]);

        // Retorna a nova variação em porcentagem
        return $novaVariacao * 100;
    }

    public function updateAccountsStocksPrice(): void
    {
        $accountStocks = $this->accounts()->get();
        $currDayVariation = $this->daily_variation / 100;
        //  talvez seja seja o caso de criar uma coluna de variation no pivot
        //  por que aí eu calcularia uma única vez o quanto a ação variou desde a compra

        //        foreach ($accountStocks as $accountStock) {
        //            $currentPrice = $accountStock->current_price * (1 + $currDayVariation);
        //            $accountStock->update(['current_price' => $currentPrice]);
        //        }
    }
}
