<?php

namespace Tests\Unit\FixedIncome;

use App\Models\Account\InvestmentAccount;
use App\Models\FixedIncome;
use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttachFixedIncomeTest extends TestCase
{
    use HasUuids, RefreshDatabase;

    public function test_attach_fixed_income_to_account(): void
    {
        $this->artisan('app:seed-fixed-income');
        $user = User::factory()->create();
        $account = InvestmentAccount::factory()->for($user)->create();
        $fixedIncome = FixedIncome::query()->where('name', 'CDB Banco A')->first();
        $account->fixedIncomes()->attach($fixedIncome, ['amount_earned' => 1000.00, 'amount_investment' => 1000.00]);

        $this->assertDatabaseHas('account_fixed_income', [
            'account_id' => $account->id,
            'fixed_income_id' => $fixedIncome->id,
            'amount_earned' => 1000.00,
            'amount_investment' => 1000.00,
        ]);
    }

    public function test_rentability_calculation(): void
    {
        // Prepare
        $this->artisan('app:seed-fixed-income');
        $user = User::factory()->create();
        $this->actingAs($user);

        $account = InvestmentAccount::factory()->for($user)->create();
        $amountInvested = 1000.00;

        //  Act
        $fixedIncome = FixedIncome::query()->where('name', 'CDB Banco A')->first();
        $this->postJson(route('fixed-income.buy', ['id' => $fixedIncome->id]), ['amount' => $amountInvested]);

        // Assert
        $expectedAmountEarned = $amountInvested + ($amountInvested * $fixedIncome->rate);
        // Simulate 1 year passing
        $days = 365;

        for ($i = 0; $i < $days; $i++) {
            $fixedIncome->calculateVolatility();
        }

        $this->assertDatabaseHas('account_fixed_income', [
            'account_id' => $account->id,
            'fixed_income_id' => $fixedIncome->id,
            'amount_earned' => $expectedAmountEarned,
            'amount_investment' => $amountInvested,
        ]);
    }
}
