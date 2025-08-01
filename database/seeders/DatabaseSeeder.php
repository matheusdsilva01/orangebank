<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $origin = User::factory()->has(Account::factory())->create();
        $user = User::factory()->has(Account::factory())->create();
        Account::factory()->for($user)->create();
        Transaction::factory()->create([
            'from_account_id' => $origin->accounts->first()->id,
            'to_account_id' => Account::factory()->create()->id,
        ]);
    }
}
