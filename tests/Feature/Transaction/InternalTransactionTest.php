<?php

namespace Transaction;

use App\Models\Account;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InternalTransactionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_should_to_do_transaction_between_current_account_to_investment_account_of_same_user(): void
    {
        // Prepare
        $user = User::factory()->create();
        $currentAccount = Account::factory()->for($user)->create(['type' => 'current']);
        $investmentAccount = Account::factory()->for($user)->create(['type' => 'investment']);
        $payload = [
            'fromAccountId' => $currentAccount->id,
            'toAccountId' => $investmentAccount->id,
            'amount' => 100.00,
        ];
        // Act
        $this->postJson(route('transaction.create'), $payload);
        // Assert
        $this->assertDatabaseHas('transactions', $payload);
    }
}
