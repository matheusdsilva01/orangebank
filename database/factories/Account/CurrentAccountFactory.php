<?php

namespace Database\Factories\Account;

use App\Models\Account\CurrentAccount;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CurrentAccount>
 */
class CurrentAccountFactory extends AccountFactory
{
    protected $model = CurrentAccount::class;
}
