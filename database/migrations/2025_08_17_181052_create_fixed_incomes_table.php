<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fixed_incomes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('type'); //  App\Enums\FixedIncomeType.php
            $table->string('rate');
            $table->string('rateType'); //  App\Enums\FixedIncomeRateType
            $table->dateTime('maturity');
            $table->string('minimumInvestment');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fixed_incomes');
    }
};
