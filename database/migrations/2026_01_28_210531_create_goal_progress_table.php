<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('goal_progress', function (Blueprint $table) {
            $table->integer('progress');
            $table->foreignId('goal_id')->constrained('goals');
            $table->string('entity_id');
            $table->boolean('completed')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('goal_progress');
    }
};
