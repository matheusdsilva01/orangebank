<?php

namespace App\Console\Commands;

use App\Models\Stock;
use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\File;

use function Laravel\Prompts\progress;

class SeedFixedIncome extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:seed-fixed-income';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed the fixed income JSON on Database';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $filepath = resource_path('/json/assets-mock.json');
        if (! File::exists($filepath)) {
            $this->error("Json file with mock fixed income not found in {$filepath}");

            return self::FAILURE;
        }
        $this->info("Json file found in {$filepath}");
        try {
            $jsonData = File::json($filepath);
            $this->info('JSON Data receive from Mock');
            $fixedIncome = $jsonData['fixedIncome'];
            $progressBar = progress(label: 'Seeding fixed income in DB', steps: count($fixedIncome));
            foreach ($fixedIncome as $item) {
                Stock::updateOrCreate(
                    ['' => $item['symbol']],
                    [
                        'symbol' => $item['symbol'],
                        'name' => $item['name'],
                        'sector' => $item['sector'],
                        'current_price' => $item['currentPrice'],
                        'daily_variation' => $item['dailyVariation'],
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
