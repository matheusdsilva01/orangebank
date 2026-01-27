<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\File;

use function Laravel\Prompts\progress;

class SeedUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:seed-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed the user JSON on Database';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $filepath = resource_path('/json/users-mock.json');
        if (! File::exists($filepath)) {
            $this->error("Json file with mock users not found in {$filepath}");

            return self::FAILURE;
        }
        $this->info("Json file found in {$filepath}");
        try {
            $jsonData = File::json($filepath);
            $this->info('JSON Data receive from Mock');
            $users = $jsonData['users'];
            $progressBar = progress(label: 'Seeding users in DB', steps: count($users));
            foreach ($users as $user) {
                User::updateOrCreate(
                    ['email' => $user['email'], 'cpf' => $user['cpf']],
                    [
                        'cpf' => $user['cpf'],
                        'name' => $user['name'],
                        'email' => $user['email'],
                        'birth_date' => $user['birthDate'],
                        'password' => '123456',
                    ]);
                $progressBar->advance();
            }
            $progressBar->finish();
            $this->info('Database populated with JSON data');

            return self::SUCCESS;
        } catch (FileNotFoundException $e) {
            $this->error($e->getMessage());

            return self::FAILURE;
        }
    }
}
