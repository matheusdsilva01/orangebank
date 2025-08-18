<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('account_fixed_income', function (Blueprint $table) {
            $table->uuid('account_id');
            $table->uuid('fixed_income_id');
            $table->float('value', 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('account_fixed_income');
    }
};
