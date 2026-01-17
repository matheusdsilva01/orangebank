<?php

namespace App\Console\Commands;

use App\Models\FixedIncome;
use Illuminate\Console\Command;

use function Laravel\Prompts\progress;

class CalculateVariationFixedIncome extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:calculate-variation-fixed-income';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate daily variation for all fixed incomes';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $fixedIncomes = FixedIncome::query()->where('maturity', '<', now())->get();
        $progressBar = progress(label: 'Calculating fixed income variations', steps: count($fixedIncomes));
        foreach ($fixedIncomes as $fixedIncome) {
            $fixedIncome->calculateVolatility();
            $progressBar->advance();
        }
    }
}
