<?php

namespace App\Models;

use App\Models\Account\Account;
use App\Models\Account\CurrentAccount;
use App\Models\Account\InvestmentAccount;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * @property string $id
 * @property CurrentAccount|null $currentAccount
 * @property InvestmentAccount|null $investmentAccount
 */
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasUuids;

    protected $guarded = ['id'];

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'cpf',
        'birth_date',
        'password',
    ];

    /**
     * @return HasOne<CurrentAccount, $this>
     */
    public function currentAccount(): HasOne
    {
        return $this->hasOne(CurrentAccount::class);
    }

    /**
     * @return HasOne<InvestmentAccount, $this>
     */
    public function investmentAccount(): HasOne
    {
        return $this->hasOne(InvestmentAccount::class);
    }

    /**
     * @return HasMany<Account, $this>
     */
    public function accounts(): HasMany
    {
        return $this->hasMany(Account::class);
    }

    /**
     * @return MorphMany<Action, $this>
     */
    public function actions(): MorphMany
    {
        return $this->morphMany(Action::class, 'entity');
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }
}
