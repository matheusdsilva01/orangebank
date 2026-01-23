<?php

namespace App\Models\Account;

use App\Casts\MoneyCast;
use App\Enums\AccountType;
use App\Models\Transaction;
use App\Models\User;
use App\Support\MoneyHelper;
use Brick\Money\Money;
use Database\Factories\Account\AccountFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Arr;

/**
 * @property Money $balance
 * @property string $id
 */
class Account extends Model
{
    /** @use HasFactory<AccountFactory> */
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
        'balance',
    ];

    protected function casts(): array
    {
        return [
            'type' => AccountType::class,
            'balance' => MoneyCast::class,
        ];
    }

    public function getLabel(): string
    {
        return AccountType::fromModel($this::class)->getLabel();
    }

    /**
     * Debit the account by a given amount.
     */
    public function debit(float $amount): void
    {
        $this->balance = $this->balance->minus(MoneyHelper::of($amount));
        $this->save();
    }

    /**
     * Credit the account by a given amount.
     */
    public function credit(float $amount): void
    {
        $this->balance = $this->balance->plus(MoneyHelper::of($amount));
        $this->save();
    }

    public function newInstance($attributes = [], $exists = false): static
    {
        /** @var static $model */
        $model = ! isset($attributes['type']) ?
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

    /**
     * @return HasMany<Transaction, $this>
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'from_account_id')
            ->orWhere('to_account_id', $this->id);
    }

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
