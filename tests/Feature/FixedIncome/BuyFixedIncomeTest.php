<?php

use App\Models\Account\InvestmentAccount;
use App\Models\FixedIncome;
use App\Models\User;
use App\Support\MoneyHelper;

test('attach fixed income to account', function (): void {
    // Prepare
    $user = User::factory()->create();
    $this->actingAs($user);

    $account = InvestmentAccount::factory()->for($user)->create(['balance' => '500000000']);
    $fixedIncome = FixedIncome::factory()->create([
        'name' => 'CDB Test 12%',
        'type' => 'cdb',
        'rate' => 0.12,
        'rateType' => 'pre',
        'minimumInvestment' => '10000000',
    ]);

    // Act
    $this->post(route('fixed-income.buy', ['id' => $fixedIncome->id]), [
        'amount' => 1000.00,
    ]);

    // Assert
    $this->assertDatabaseHas('account_fixed_income', [
        'account_id' => $account->id,
        'fixed_income_id' => $fixedIncome->id,
        'amount_earned' => '10000000',
        'amount_investment' => '10000000',
    ]);
});

test('pre fixed income calculates daily earnings with 12 percentage rate', function (): void {
    // Prepare
    $user = User::factory()->create();
    $this->actingAs($user);
    $account = InvestmentAccount::factory()->for($user)->create();

    $fixedIncome = FixedIncome::factory()->create([
        'name' => 'CDB Test 12%',
        'type' => 'cdb',
        'rate' => 0.12,
        'rateType' => 'pre',
        'minimumInvestment' => '10000000',
    ]);

    // Act
    $amountInvested = 1000.00;
    $this->post(route('fixed-income.buy', ['id' => $fixedIncome->id]), [
        'amount' => $amountInvested,
    ]);

    $days = 365;
    for ($i = 0; $i < $days; $i++) {
        $fixedIncome->calculateVolatility();
    }

    $expectedAmountEarned = 1120;

    $this->assertDatabaseHas('account_fixed_income', [
        'account_id' => $account->id,
        'fixed_income_id' => $fixedIncome->id,
        'amount_investment' => '10000000',
        'amount_earned' => (string) MoneyHelper::of($expectedAmountEarned)->getUnscaledAmount(),
    ]);
});
