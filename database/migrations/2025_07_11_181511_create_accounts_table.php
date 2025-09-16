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
        Schema::create('accounts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type'); // App\Enums\AccountType.php
            $table->string('number', 12)->unique();
            $table->decimal('balance', 19, 4)->default(0);
            $table->foreignUuid('user_id')->constrained();
            $table->timestamps();
            $table->unique(['type', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
