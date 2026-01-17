<?php

use App\Models\Account\InvestmentAccount;
use App\Models\AccountFixedIncome;
use App\Models\User;
use App\Support\MoneyHelper;

test('should sell the fixed income', function (): void {
    // Prepare
    $user = User::factory()->create();
    $investmentAccount = InvestmentAccount::factory()->for($user)->create([
        'balance' => (string) MoneyHelper::of(1000)->getUnscaledAmount(),
    ]);

    $amountEarned = 1200;
    $purchasedFixedIncome = AccountFixedIncome::factory()->create([
        'account_id' => $investmentAccount->id,
        'amount_earned' => (string) MoneyHelper::of($amountEarned)->getUnscaledAmount(),
        'amount_investment' => (string) MoneyHelper::of(1000)->getUnscaledAmount(),
    ]);

    $this->actingAs($user);

    // Act
    $response = $this->post(route('fixed-income.sell', [
        'accountFixedIncome' => $purchasedFixedIncome->id,
    ]));
    $response->assertRedirect();
    // Assert
    $expectedAmountEarned = MoneyHelper::of(2156);
    $this->assertDatabaseHas('accounts', [
        'balance' => (string) $expectedAmountEarned->getUnscaledAmount(),
    ]);
});
