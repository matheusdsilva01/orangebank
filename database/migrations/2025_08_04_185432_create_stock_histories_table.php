<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_histories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->float('daily_variation');
            $table->float('daily_price');
            $table->foreignUuid('stock_id')->constrained('stocks');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_histories');
    }
};
