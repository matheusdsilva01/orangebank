<?php

namespace Tests\Unit\Transactions;

use App\Models\Account\CurrentAccount;
use App\Models\Account\InvestmentAccount;
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
        $currentAccount = CurrentAccount::factory()->for($user)->create();
        $investmentAccount = InvestmentAccount::factory()->for($user)->create();
        $this->actingAs($user);

        $oldCurrentBalance = $currentAccount->balance;
        $oldInvestmentBalance = $investmentAccount->balance;

        $payload = [
            'mode' => 'current-investment',
            'amount' => 100,
        ];

        // Act
        $this->postJson(route('transfer.internal'), $payload);

        // Assert
        $this->assertEquals($currentAccount->refresh()->balance, $oldCurrentBalance - $payload['amount']);
        $this->assertEquals($investmentAccount->refresh()->balance, $oldInvestmentBalance + $payload['amount']);

        $expected = [
            'from_account_id' => $currentAccount->id,
            'to_account_id' => $investmentAccount->id,
            'amount' => $payload['amount'],
        ];

        $this->assertDatabaseHas('transactions', $expected);
    }
}
