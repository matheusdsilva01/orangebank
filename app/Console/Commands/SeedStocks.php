<?php

namespace App\Console\Commands;

use App\Models\Stock;
use App\Support\MoneyHelper;
use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\File;

use function Laravel\Prompts\progress;

class SeedStocks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:seed-stocks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed the stocks JSON on Database';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $filepath = resource_path('/json/assets-mock.json');
        if (! File::exists($filepath)) {
            $this->error("Json file with mock users not found in {$filepath}");

            return self::FAILURE;
        }
        $this->info("Json file found in {$filepath}");
        try {
            $jsonData = File::json($filepath);
            $this->info('JSON Data receive from Mock');
            $stocks = $jsonData['stocks'];
            $progressBar = progress(label: 'Seeding stocks in DB', steps: count($stocks));
            foreach ($stocks as $stock) {
                Stock::updateOrCreate(
                    ['symbol' => $stock['symbol']],
                    [
                        'symbol' => $stock['symbol'],
                        'name' => $stock['name'],
                        'sector' => $stock['sector'],
                        'current_price' => (string) MoneyHelper::of($stock['currentPrice'])->getUnscaledAmount(),
                        'daily_variation' => $stock['dailyVariation'],
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
