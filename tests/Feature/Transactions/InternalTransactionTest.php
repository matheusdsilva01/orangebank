<?php

use App\Models\Account\CurrentAccount;
use App\Models\Account\InvestmentAccount;
use App\Models\User;
use App\Support\MoneyHelper;

test('should to do transaction between current account to investment account of same user', function (): void {
    // Prepare
    $user = User::factory()->create();
    $currentAccount = CurrentAccount::factory()->for($user)->create(['balance' => '1100000']);
    $investmentAccount = InvestmentAccount::factory()->for($user)->create(['balance' => '10000']);
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
    expect($oldCurrentBalance->minus($payload['amount'])->isEqualTo($currentAccount->refresh()->balance))
        ->and($oldInvestmentBalance->plus($payload['amount']))->toEqual($investmentAccount->refresh()->balance);

    $expected = [
        'from_account_id' => $currentAccount->id,
        'to_account_id' => $investmentAccount->id,
        'amount' => '1000000',
    ];
    $this->assertDatabaseHas('transactions', $expected);
});
