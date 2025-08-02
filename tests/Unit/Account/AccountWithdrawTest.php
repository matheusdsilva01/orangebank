<?php

namespace Tests\Unit\Account;

use App\Models\Account;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccountWithdrawTest extends TestCase
{
    use refreshDatabase;

    public function test_to_be_to_do_withdraw_from_current_account_when_account_has_sufficient_balance()
    {
        //  Prepare
        $user = User::factory()->create();
        $accountCurrent = Account::factory()->for($user)->createCurrent()->create();
        $payload = [
            'number' => $accountCurrent->number,
            'amount' => 100.00,
        ];
        //  Act
        $this->postJson(route('account.withdraw', $payload));
        //  Assert
        $oldBalance = $accountCurrent->balance;
        $expectedBalance = $oldBalance - $payload['amount'];
        $expectedTransaction = [
            'from_account_id' => $accountCurrent->id,
            'to_account_id' => null,
            'type' => 'withdraw',
            'tax' => 0,
            'amount' => $payload['amount'],
        ];

        $this->assertEquals($accountCurrent->refresh()->balance, $expectedBalance);
        $this->assertDatabaseHas('transactions', $expectedTransaction);
    }

    public function test_to_not_able_withdraw_amount_from_investment_account()
    {
        //  Prepare
        $user = User::factory()->create();
        $accountInvestment = Account::factory()->for($user)->createInvestment()->create();
        $payload = [
            'number' => $accountInvestment->number,
            'amount' => 100.00,
        ];
        //  Act
        $response = $this->postJson(route('account.withdraw', $payload));
        //  Assert
        $oldBalance = $accountInvestment->balance;
        $response->assertUnprocessable();
        $this->assertEquals($accountInvestment->refresh()->balance, $oldBalance);
        $this->assertDatabaseEmpty('transactions');
    }
}
