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
        
        // Run the Lighthouse audit for the candidate's website
        $json = $lighthouse->run($this->candidate->website);

        // Decode the JSON result into an associative array
        $data = json_decode($json, true);

        // Store the Lighthouse category scores and their contributing issues
        foreach ($data['categories'] as $category) {

            // Store the overall category score
            $categoryScore = $category['score'] !== null
                ? round($category['score'] * 100)
                : null;

            // Determine the score to store for the category
            $this->candidate->scanResults()->create([
                'check' => 'Lighthouse: ' . $category['title'],
                'passed' => true,
                'message' => $categoryScore !== null
                    ? $category['title'] . ' score: ' . $categoryScore . '/100'
                    : $category['title'] . ' score unavailable',
                'score' => 0,
                'details' => $category,
            ]);

            // Store individual issues that contribute to the category score
            foreach ($category['auditRefs'] as $auditRef) {

                // Retrieve the detailed audit information
                $audit = $data['audits'][$auditRef['id']] ?? null;

                // Ignore missing audits, unavailable scores and passing audits
                if (
                    $audit === null ||
                    ! isset($audit['score']) ||
                    $audit['score'] === null ||
                    $audit['score'] >= 1
                ) {
                    continue;
                }

                // Build a useful explanation of the issue
                $message = $audit['description'] ?? '';

                // Prepend the display value to the message if available
                if (! empty($audit['displayValue'])) {
                    $message = $audit['displayValue'] . "\n" . $message;
                }

                // Store the individual audit result for the candidate
                $this->candidate->scanResults()->create([
                    'check' => 'Lighthouse: ' . $category['title'] . ' - ' . $audit['title'],
                    'passed' => false,
                    'message' => $message,
                    'score' => 0,
                    'details' => $audit,
                ]);
            }
        }
    }
}