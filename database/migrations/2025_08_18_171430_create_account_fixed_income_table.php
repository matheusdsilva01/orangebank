<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('account_fixed_income', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('account_id');
            $table->uuid('fixed_income_id');
            $table->decimal('amount_earned', 19, 4);
            $table->decimal('amount_investment', 19, 4);
            $table->timestamp('purchased_date')->nullable();
            $table->timestamp('sale_date')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('account_fixed_income');
    }
};
