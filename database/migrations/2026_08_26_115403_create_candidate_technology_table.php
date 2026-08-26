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
        Schema::create('candidate_technology', function (Blueprint $table) {
            $table->foreignId('candidate_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('technology_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->unique(['candidate_id', 'technology_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidate_technology');
    }
};
