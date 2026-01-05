<?php

use App\Models\Account\CurrentAccount;
use App\Models\User;

test('should to do external transaction between current account to another current account of different users', function (): void {
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

    expect($currentAccountUserSender->refresh()->balance)->toEqual($oldBalanceSender - $amountWithTaxDiscount);
    expect($currentAccountUserReceiver->refresh()->balance)->toEqual($oldBalanceReceiver + $payload['amount']);
    $this->assertDatabaseHas('transactions', $expected);
});
