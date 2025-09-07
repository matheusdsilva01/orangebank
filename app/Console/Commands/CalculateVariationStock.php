<?php

namespace App\Console\Commands;

use App\Models\Stock;
use Illuminate\Console\Command;

use function Laravel\Prompts\progress;

class CalculateVariationStock extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:calculate-variation-stock';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate daily variation for all stocks';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $stocks = Stock::all();
        $progressBar = progress(label: 'Calculating stock variations', steps: count($stocks));
        foreach ($stocks as $stock) {
            $stock->calculateVolatility();
            $progressBar->advance();
        }
    }
}
