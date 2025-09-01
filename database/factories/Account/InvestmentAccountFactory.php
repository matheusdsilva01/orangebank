<?php

namespace Database\Factories\Account;

use App\Enums\AccountType;
use App\Models\Account\InvestmentAccount;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<InvestmentAccount>
 */
class InvestmentAccountFactory extends AccountFactory
{
    protected $model = InvestmentAccount::class;
}
