<?php

namespace App\Models\Account;

use App\Enums\AccountType;
use App\Enums\TransactionType;
use App\Exceptions\AccountException;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
        'balance',
    ];

    public function getLabel(): string
    {
        return AccountType::fromModel($this::class)->getLabel();
    }

    /**
     * @throws AccountException
     */
    public function internalTransfer(array $payload): Transaction
    {
        $amount = $payload['amount'];
        $destinationType = $payload['destination'];
        $fromAccount = $this;
        $toAccount = AccountType::from($destinationType)
            ->getModel()::query()
            ->where('user_id', $fromAccount->user_id)
            ->get()
            ->first();
        if (! $toAccount) {
            throw AccountException::accountNotFound();
        }

        if ($toAccount->user_id !== $fromAccount->user_id) {
            throw AccountException::internalTransfersCanOnlyBeMadeBetweenAccountsOfTheSameUser();
        }
        if ($toAccount::class === $fromAccount::class) {
            throw AccountException::cannotTransferBetweenSameTypeAccounts();
        }
        if ($fromAccount->balance < $amount) {
            throw AccountException::insufficientBalance();
        }

        $fromAccount->decrement('balance', $amount);
        $toAccount->increment('balance', $amount);

        return Transaction::create([
            'from_account_id' => $this->id,
            'to_account_id' => $toAccount->id,
            'amount' => $amount,
            'type' => TransactionType::Internal,
            'tax' => 0,
        ]);
    }

    public function newInstance($attributes = [], $exists = false): static
    {
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

    protected function casts(): array
    {
        return [
            'type' => AccountType::class,
        ];
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'from_account_id')
            ->orWhere('to_account_id', $this->id);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
