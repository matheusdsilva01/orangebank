<?php

namespace Tests\Unit\Transactions;

use App\Models\Account\CurrentAccount;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExternalTransactionTest extends TestCase
{
    use refreshDatabase;

    public function test_should_to_do_external_transaction_between_current_account_to_another_current_account_of_different_users(): void
    {
        // Prepare
        $userSender = User::factory()->create();
        $userReceiver = User::factory()->create();

        $currentAccountUserSender = CurrentAccount::factory()->for($userSender)->create();
        $currentAccountUserReceiver = CurrentAccount::factory()->for($userReceiver)->create();

        $oldBalanceSender = $currentAccountUserSender->balance;
        $oldBalanceReceiver = $currentAccountUserReceiver->balance;

        $this->actingAs($userSender);

        $payload = [
            'destination' => $currentAccountUserReceiver->number,
            'amount' => 100.00,
        ];
        // Act
        $this->postJson(route('transfer.external'), $payload);

        // Assert
        $amountWithTaxDiscount = $payload['amount'] + (50 / 100);

        $expected = [
            'from_account_id' => $currentAccountUserSender->id,
            'to_account_id' => $currentAccountUserReceiver->id,
            'amount' => $payload['amount'],
        ];

        $this->assertEquals($oldBalanceSender - $amountWithTaxDiscount, $currentAccountUserSender->refresh()->balance);
        $this->assertEquals($oldBalanceReceiver + $payload['amount'], $currentAccountUserReceiver->refresh()->balance);
        $this->assertDatabaseHas('transactions', $expected);
    }
}
