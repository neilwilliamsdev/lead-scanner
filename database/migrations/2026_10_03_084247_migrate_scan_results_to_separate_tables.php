<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('scan_results')
            ->orderBy('id')
            ->each(function ($result) {
                if (str_starts_with($result->check, 'Lighthouse: ')) {
                    DB::table('lighthouse_results')->insert([
                        'candidate_id' => $result->candidate_id,
                        'check' => $result->check,
                        'passed' => $result->passed,
                        'message' => $result->message,
                        'details' => $result->details,
                        'created_at' => $result->created_at,
                        'updated_at' => $result->updated_at,
                    ]);

                    return;
                }

                DB::table('website_check_results')->insert([
                    'candidate_id' => $result->candidate_id,
                    'check' => $result->check,
                    'passed' => $result->passed,
                    'message' => $result->message,
                    'score' => $result->score,
                    'created_at' => $result->created_at,
                    'updated_at' => $result->updated_at,
                ]);
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

    }
};
