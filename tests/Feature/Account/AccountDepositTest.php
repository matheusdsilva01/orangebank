<?php

use App\Models\Account\CurrentAccount;
use App\Models\Account\InvestmentAccount;
use App\Models\User;

test('to be to do deposit to current account', function (): void {
    //  Prepare
    $user = User::factory()->create();
    $account = CurrentAccount::factory()->for($user)->create(['balance' => '5000000']);
    $previousBalance = $account->balance;
    $payload = [
        'amount' => 100.00,
    ];
    $this->actingAs($user);

    //  Act
    $this->postJson(route('account.deposit'), $payload);

    //  Assert
    $expectedBalance = $previousBalance->plus($payload['amount']);
    expect($account->refresh()->balance)->toEqual($expectedBalance);
    $this->assertDatabaseCount('transactions', 1);
});

test('to not deposit without current account', function (): void {
    //  Prepare
    $user = User::factory()->create();
    $accountInvestment = InvestmentAccount::factory()->for($user)->create();
    $oldBalance = $accountInvestment->balance;
    $this->actingAs($user);
    $payload = [
        'amount' => 100.00,
    ];

    //  Act
    $response = $this->postJson(route('account.deposit', $payload));

    //  Assert
    $response->assertNotFound();
    expect($oldBalance)->toEqual($accountInvestment->refresh()->balance);
    $this->assertDatabaseEmpty('transactions');
});
