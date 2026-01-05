<?php

use App\Models\Account\CurrentAccount;
use App\Models\Account\InvestmentAccount;
use App\Models\User;

test('should to do transaction between current account to investment account of same user', function (): void {
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
    expect($oldCurrentBalance - $payload['amount'])->toEqual((float) $currentAccount->refresh()->balance);
    expect($oldInvestmentBalance + $payload['amount'])->toEqual((float) $investmentAccount->refresh()->balance);

    $expected = [
        'from_account_id' => $currentAccount->id,
        'to_account_id' => $investmentAccount->id,
        'amount' => $payload['amount'],
    ];
    $this->assertDatabaseHas('transactions', $expected);
});
