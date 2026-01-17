<?php

use App\Models\Account\CurrentAccount;
use App\Models\User;

test('should to do external transaction between current account to another current account of different users', function (): void {
    // Prepare
    $userSender = User::factory()->create();
    $userReceiver = User::factory()->create();

    $currentAccountUserSender = CurrentAccount::factory()->for($userSender)->create(['balance' => '3000000']);
    $currentAccountUserReceiver = CurrentAccount::factory()->for($userReceiver)->create(['balance' => '3000000']);

    $this->actingAs($userSender);

    $payload = [
        'destination' => $currentAccountUserReceiver->number,
        'amount' => 100.00,
    ];

    // Act
    $this->postJson(route('transfer.external'), $payload);

    // Assert
    $expected = [
        'from_account_id' => $currentAccountUserSender->id,
        'to_account_id' => $currentAccountUserReceiver->id,
        'amount' => '1000000',
    ];

    expect((string) $currentAccountUserSender->refresh()->balance->getUnscaledAmount())->toEqual('1995000')
        ->and((string) $currentAccountUserReceiver->refresh()->balance->getUnscaledAmount())->toEqual('4000000');
    $this->assertDatabaseHas('transactions', $expected);
});
