<?php

namespace Tests\Unit\transactions;

use App\Models\Account;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExternalTransactionTest extends TestCase
{
    use refreshDatabase;

    public function test_should_to_do_transaction_between_current_account_to_another_current_account_of_different_users(): void
    {
        // Prepare
        $userSender = User::factory()->create();
        $userReceiver = User::factory()->create();
        $currentAccountUserSender = Account::factory()->for($userSender)->createCurrent()->create();
        $currentAccountUserReceiver = Account::factory()->for($userReceiver)->createCurrent()->create();

        $payload = [
            'fromAccountId' => $currentAccountUserSender->id,
            'toAccountId' => $currentAccountUserReceiver->id,
            'amount' => 100.00,
        ];

        // Act
        $this->postJson(route('transaction.create', $payload));
        // Assert
        $oldBalanceSender = $currentAccountUserSender->balance;
        $oldBalanceReceiver = $currentAccountUserReceiver->balance;

        $amountWithTaxDiscount = $payload['amount'] + (50 / 100);

        $expected = [
            'from_account_id' => $payload['fromAccountId'],
            'to_account_id' => $payload['toAccountId'],
            'amount' => $payload['amount'],
        ];

        $this->assertEquals($currentAccountUserSender->refresh()->balance, $oldBalanceSender - $amountWithTaxDiscount);
        $this->assertEquals($currentAccountUserReceiver->refresh()->balance, $oldBalanceReceiver + $payload['amount']);
        $this->assertDatabaseHas('transactions', $expected);
    }
}
