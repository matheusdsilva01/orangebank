<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('from_account_id')
                ->nullable()
                ->constrained('accounts', 'id')
                ->onDelete('cascade');
            $table->foreignUuid('to_account_id')
                ->nullable()
                ->constrained('accounts', 'id')
                ->onDelete('cascade');
            $table->string('amount');
            $table->string('type'); //  App\Enums\TransactionType.php
            $table->decimal('tax', 8, 4); // de 0.0001 to 9999.9999
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
