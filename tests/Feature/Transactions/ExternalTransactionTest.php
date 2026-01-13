<?php

use App\Models\Account\CurrentAccount;
use App\Models\User;
use App\Support\MoneyHelper;
use Brick\Math\RoundingMode;

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
    // (50 / 100) 0,5% tax discount
    $amountWithTaxDiscount = MoneyHelper::of($payload['amount'])->dividedBy(1.0005, RoundingMode::HALF_EVEN)->getAmount();

    $expected = [
        'from_account_id' => $currentAccountUserSender->id,
        'to_account_id' => $currentAccountUserReceiver->id,
        'amount' => '1000000',
    ];

    expect($currentAccountUserSender->refresh()->balance)->toEqual($oldBalanceSender->minus($amountWithTaxDiscount))
        ->and($currentAccountUserReceiver->refresh()->balance)->toEqual($oldBalanceReceiver->plus(MoneyHelper::of($payload['amount'])->getAmount()));
    $this->assertDatabaseHas('transactions', $expected);
});
