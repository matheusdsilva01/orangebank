<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('account_stock', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('account_id')->constrained('accounts', 'id');
            $table->foreignUuid('stock_id')->constrained('stocks', 'id');
            $table->integer('quantity');
            $table->float('purchase_price', 4);
            $table->float('sale_price', 4)->nullable();
            $table->timestamp('purchase_date')->nullable();
            $table->timestamp('sale_date')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('accounts_stocks');
    }
};
