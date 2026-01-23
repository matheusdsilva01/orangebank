<?php

namespace App\Models;

use App\Casts\MoneyCast;
use App\Enums\TransactionType;
use App\Models\Account\Account;
use Brick\Money\Money;
use Database\Factories\TransactionFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property Money $amount
 */
class Transaction extends Model
{
    /** @use HasFactory<TransactionFactory> */
    use HasFactory, HasUuids;

    protected $table = 'transactions';

    public function getComponent(): string
    {
        return match ($this->type) {
            TransactionType::Internal => 'transaction-item.internal',
            TransactionType::External => 'transaction-item.external',
            TransactionType::Deposit => 'transaction-item.deposit',
            TransactionType::Withdraw => 'transaction-item.withdraw',
        };
    }

    /**
     * @return Attribute<User, void>
     */
    protected function sender(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->fromAccount->user
        );
    }

    /**
     * @return Attribute<User, void>
     */
    protected function receive(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->toAccount->user
        );
    }

    /**
     * @return Attribute<bool|null, void>
     */
    protected function isSender(): Attribute
    {

        return Attribute::make(
            get: function () {
                if ($this->type !== TransactionType::External) {
                    return null;
                }
                $authUser = auth()->user();

                return $this->fromAccount->user->id === $authUser->id;
            },
        );
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'from_account_id',
        'to_account_id',
        'amount',
        'type',
        'tax',
    ];

    protected $casts = [
        'type' => TransactionType::class,
        'created_at' => 'custom_datetime',
        'updated_at' => 'custom_datetime',
        'amount' => MoneyCast::class,
    ];

    /** @return BelongsTo<Account, $this> */
    public function fromAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'from_account_id');
    }

    /** @return BelongsTo<Account, $this> */
    public function toAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'to_account_id');
    }
}
