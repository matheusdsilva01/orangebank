<?php

namespace App\Models\Account;

use App\Enums\AccountType;
use App\Models\FixedIncome;
use App\Models\Stock;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Arr;

class Account extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = ['id'];

    protected $table = 'accounts';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'balance'
    ];

    public function newInstance($attributes = [], $exists = false): static
    {
        $model = !isset($attributes['type']) ?
            new static($attributes) :
            new (AccountType::from($attributes['type'])->getModel())($attributes);

        $model->exists = $exists;

        $model->setConnection(
            $this->getConnectionName()
        );

        $model->setTable($this->getTable());

        $model->mergeCasts($this->casts);

        $model->fill((array) $attributes);

        return $model;
    }


    public function newFromBuilder($attributes = [], $connection = null): static
    {
        $attributes = (array) $attributes;
        $model = $this->newInstance([
            'type' => $attributes['type'] ?? null,
        ], true);

        $model->setRawAttributes(Arr::except($attributes, 'type'), true);

        $model->setConnection($connection ?: $this->getConnectionName());

        $model->fireModelEvent('retrieved', false);

        return $model;
    }

    protected function casts(): array
    {
        return [
            'type' => AccountType::class,
        ];
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'account_id');
    }

    public function stocks(): BelongsToMany
    {
        return $this->belongsToMany(Stock::class)
            ->withPivot([
                'quantity',
                'purchase_price',
                'sale_price',
                'purchase_date',
                'sale_date',
            ]);
    }

    public function fixedIncomes(): BelongsToMany
    {
        return $this->belongsToMany(FixedIncome::class)
//            ->using(new class extends Pivot{
//                use HasUuids;
//            })
            ->withPivot([
                'value',
            ]);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
