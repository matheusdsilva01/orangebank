<?php

namespace Tests\Unit\Account;

use App\Models\Account\CurrentAccount;
use App\Models\Account\InvestmentAccount;
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
        $account = CurrentAccount::factory()->for($user)->create();
        $previousBalance = $account->balance;
        $payload = [
            'amount' => 100.00,
        ];
        $this->actingAs($user);
        //  Act
        $this->postJson(route('account.deposit'), $payload);
        //  Assert
        $expectedBalance = $previousBalance + $payload['amount'];
        $this->assertEquals($expectedBalance, $account->refresh()->balance);
        $this->assertDatabaseCount('transactions', 1);
    }

    public function test_to_not_deposit_without_current_account()
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
        $response = $this->postJson(route('account.deposit', $payload));
        //  Assert
        $response->assertNotFound();
        $this->assertEquals($accountInvestment->refresh()->balance, $oldBalance);
        $this->assertDatabaseEmpty('transactions');
    }
}
