<?php

namespace App\Jobs;

use App\Models\Candidate;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Website\LighthouseRunner;

class RunLighthouseAudit implements ShouldQueue
{
    use Queueable;

    /**
     * The candidate to audit.
     *
     * @var Candidate
     */
    public function __construct(
        public Candidate $candidate
    ) {
    }

    /**
     * Execute the job.
     */
    public function handle(LighthouseRunner $lighthouse): void
    {
            
        $json = $lighthouse->run($this->candidate->website);

        $data = json_decode($json, true);

        foreach ($data['categories'] as $category) {
            $this->candidate->scanResults()->create([
                'check' => 'Lighthouse: ' . $category['title'],
                'passed' => true,
                'message' => $category['title'] . ' score: ' . round($category['score'] * 100) . '/100',
                'score' => 0,
            ]);
        }
    }
}