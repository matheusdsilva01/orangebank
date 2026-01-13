<?php

use App\Enums\TransactionType;
use App\Models\Account\CurrentAccount;
use App\Models\Account\InvestmentAccount;
use App\Models\User;

test('to be to do withdraw from current account when account has sufficient balance', function (): void {
    //  Prepare
    $user = User::factory()->create();
    $accountCurrent = CurrentAccount::factory(['balance' => '12005000'])->for($user)->create();
    $oldBalance = $accountCurrent->balance;

    $this->actingAs($user);

    $payload = [
        'amount' => 100.50,
    ];

    //  Act
    $this->postJson(route('account.withdraw', $payload));

    //  Assert
    $expectedBalance = $oldBalance->minus($payload['amount']);

    $expectedTransaction = [
        'from_account_id' => $accountCurrent->id,
        'to_account_id' => null,
        'type' => TransactionType::Withdraw,
        'tax' => 0,
        'amount' => "1005000",
    ];

    expect($accountCurrent->refresh()->balance)->toEqual($expectedBalance);
    $this->assertDatabaseHas('transactions', $expectedTransaction);
});

test('to not able withdraw amount from investment account', function (): void {
    //  Prepare
    $user = User::factory()->create();
    $accountInvestment = InvestmentAccount::factory()->for($user)->create();
    $oldBalance = $accountInvestment->balance;

    $this->actingAs($user);

    $payload = [
        'amount' => 100.00,
    ];

    //  Act
    $response = $this->postJson(route('account.withdraw'), $payload);

    //  Assert
    $response->assertNotFound();
    expect($oldBalance)->toEqual($accountInvestment->refresh()->balance);
    $this->assertDatabaseEmpty('transactions');
});
