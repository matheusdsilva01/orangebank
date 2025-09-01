<?php

namespace Tests\Unit\transactions;

use App\Models\Account\Account;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InternalTransactionTest extends TestCase
{
    use RefreshDatabase;

    public function test_should_to_do_transaction_between_current_account_to_investment_account_of_same_user(): void
    {
        // Prepare
        $user = User::factory()->create();
        $currentAccount = Account::factory()->for($user)->create(['type' => 'current']);
        $investmentAccount = Account::factory()->for($user)->create(['type' => 'investment']);
        $payload = [
            'fromAccountId' => $currentAccount->id,
            'toAccountId' => $investmentAccount->id,
            'amount' => 100,
        ];
        // Act
        $this->postJson(route('transaction.create'), $payload);
        // Assert
        $oldCurrentBalance = $currentAccount->balance;
        $oldInvestmentBalance = $investmentAccount->balance;
        $this->assertEquals($currentAccount->refresh()->balance, $oldCurrentBalance - $payload['amount']);
        $this->assertEquals($investmentAccount->refresh()->balance, $oldInvestmentBalance + $payload['amount']);

        $expected = [
            'from_account_id' => $payload['fromAccountId'],
            'to_account_id' => $payload['toAccountId'],
            'amount' => $payload['amount'],
        ];

        $this->assertDatabaseHas('transactions', $expected);
    }
}
