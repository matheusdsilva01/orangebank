<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Transaction;
use App\Models\User;
use Artisan;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Artisan::call('app:seed-users');
        Artisan::call('app:seed-stocks');
        $origin = User::factory()->create();
        Account::factory()->for($origin)->createInvestment()->create();
        $user = User::factory()->create();
        Account::factory()->for($user)->createCurrent()->create();
        Transaction::factory()->create([
            'from_account_id' => $origin->accounts->first()->id,
            'to_account_id' => Account::factory()->create()->id,
        ]);
    }
}
