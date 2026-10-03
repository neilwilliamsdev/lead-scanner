<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lighthouse_results', function (Blueprint $table) {
            $table->dropColumn('passed');
        });
    }

    public function down(): void
    {
        Schema::table('lighthouse_results', function (Blueprint $table) {
            $table->boolean('passed')->default(false);
        });
    }
};
