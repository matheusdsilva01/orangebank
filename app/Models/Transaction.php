<?php

namespace App\Models;

use App\Enums\TransactionType;
use App\Models\Account\Account;
use Database\Factories\TransactionFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    /** @use HasFactory<TransactionFactory> */
    use HasFactory, HasUuids;

    protected $table = 'transactions';

    public function getIcon(): string
    {
        return match ($this->type) {
            TransactionType::Internal => 'heroicon-o-arrows-right-left',
            TransactionType::External => ($this->is_sender) ? 'heroicon-o-arrow-up-tray' : 'heroicon-o-arrow-down-tray',
            TransactionType::Deposit => 'heroicon-o-arrow-down-tray',
            TransactionType::Withdraw => 'heroicon-o-arrow-up-tray',
        };
    }

    protected function sender(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->fromAccount->user
        );
    }

    protected function receive(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->toAccount->user
        );
    }

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
    ];

    public function fromAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'from_account_id');
    }

    public function toAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'to_account_id');
    }
}
