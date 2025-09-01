<?php

namespace Database\Seeders;

use App\Enums\AccountType;
use App\Models\Account\CurrentAccount;
use App\Models\Account\InvestmentAccount;
use App\Models\User;
use Artisan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::query()->get()->where('email', 'tabata15@example.org');
        Artisan::call('app:seed-users');
        Artisan::call('app:seed-stocks');
        Artisan::call('app:seed-fixed-income');
        $johnUser = User::factory()->create();
        CurrentAccount::factory()->for($johnUser)->create();
        InvestmentAccount::factory()->for($johnUser)->create();
        $user = User::factory()->create([
            'email' => 'user@mail.com',
            'password' => Hash::make('password'),
        ]);
        CurrentAccount::factory()->for($user)->create();
        InvestmentAccount::factory()->for($user)->create();
    }
}
