<?php

namespace App\Jobs;

use App\Models\Business;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Website\LighthouseRunner;

class RunLighthouseAudit implements ShouldQueue
{
    use Queueable;

    /**
     * The business to audit.
     *
     * @var Business
     */
    public function __construct(
        public Business $business
    ) {
    }

    /**
     * Execute the job.
     */
    public function handle(LighthouseRunner $lighthouse): void
    {
        // Run the Lighthouse audit for the business website
        $json = $lighthouse->run($this->business->website);

        // Decode the JSON result into an associative array
        $data = json_decode($json, true);

        // Store the Lighthouse category scores and their contributing issues
        foreach ($data['categories'] as $category) {

            // Store the overall category score
            $categoryScore = $category['score'] !== null
                ? round($category['score'] * 100)
                : null;

            // Store the category result against the business
            $this->business->lighthouseResults()->create([
                'check' => 'Lighthouse: ' . $category['title'],
                'message' => $categoryScore !== null
                    ? $category['title'] . ' score: ' . $categoryScore . '/100'
                    : $category['title'] . ' score unavailable',
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

                // Store the individual audit result against the business
                $this->business->lighthouseResults()->create([
                    'check' => 'Lighthouse: ' . $category['title'] . ' - ' . $audit['title'],
                    'message' => $message,
                    'details' => $audit,
                ]);
            }
        }
    }
}