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
        Schema::create('candidates', function (Blueprint $table) {
            $table->id();

            $table->foreignId('discovery_run_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('business_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->string('name');
            $table->string('website');
            $table->string('domain')->index();
            $table->string('location')->nullable();
            $table->string('category')->nullable();
            $table->string('source');
            $table->string('source_id')->nullable();

            $table->string('status')->default('new');

            $table->boolean('website_reachable')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidates');
    }
};
