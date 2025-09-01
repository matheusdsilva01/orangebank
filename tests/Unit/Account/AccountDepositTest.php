<?php

namespace Tests\Unit\Account;

use App\Models\Account\Account;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccountDepositTest extends TestCase
{
    use RefreshDatabase;

    public function test_to_be_to_do_deposit_to_current_account()
    {
        //  Prepare
        $user = User::factory()->create();
        $account = Account::factory()->for($user)->createCurrent()->create();
        $payload = [
            'number' => $account->number,
            'amount' => 100.00,
        ];
        //  Act
        $this->postJson(route('account.deposit'), $payload);
        //  Assert
        $expectedBalance = $account->balance + $payload['amount'];
        $this->assertEquals($account->refresh()->balance, $expectedBalance);
    }

    public function test_to_not_able_deposit_amount_to_investment_account()
    {
        //  Prepare
        $user = User::factory()->create();
        $accountInvestment = Account::factory()->for($user)->createInvestment()->create();

        $payload = [
            'number' => $accountInvestment->number,
            'amount' => 100.00,
        ];
        //  Act
        $response = $this->postJson(route('account.deposit', $payload));
        //  Assert
        $oldBalance = $accountInvestment->balance;
        $response->assertUnprocessable();
        $this->assertEquals($accountInvestment->refresh()->balance, $oldBalance);
        $this->assertDatabaseEmpty('transactions');
    }
}
