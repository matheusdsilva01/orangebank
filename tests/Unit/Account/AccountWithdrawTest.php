<?php

namespace Tests\Unit\Account;

use App\Enums\TransactionType;
use App\Models\Account\CurrentAccount;
use App\Models\Account\InvestmentAccount;
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
        $accountCurrent = CurrentAccount::factory(['balance' => 1200.50])->for($user)->create();
        $oldBalance = $accountCurrent->balance;

        $this->actingAs($user);

        $payload = [
            'amount' => 100.00,
        ];
        //  Act
        $this->postJson(route('account.withdraw', $payload));
        //  Assert
        $expectedBalance = $oldBalance - $payload['amount'];

        $expectedTransaction = [
            'from_account_id' => $accountCurrent->id,
            'to_account_id' => null,
            'type' => TransactionType::Withdraw,
            'tax' => 0,
            'amount' => $payload['amount'],
        ];

        $this->assertEquals($expectedBalance, $accountCurrent->refresh()->balance);
        $this->assertDatabaseHas('transactions', $expectedTransaction);
    }

    public function test_to_not_able_withdraw_amount_from_investment_account()
    {
        //  Prepare
        $user = User::factory()->create();
        $accountInvestment = InvestmentAccount::factory()->for($user)->create();
        $oldBalance = $accountInvestment->balance;

        $this->actingAs($user);

        $payload = [
            'amount' => 100.00,
        ];

        //  Act
        $response = $this->postJson(route('account.withdraw'), $payload);

        //  Assert
        $response->assertNotFound();
        $this->assertEquals($accountInvestment->refresh()->balance, $oldBalance);
        $this->assertDatabaseEmpty('transactions');
    }
}
