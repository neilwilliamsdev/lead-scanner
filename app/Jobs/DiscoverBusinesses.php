<?php

namespace App\Jobs;

use App\Models\DiscoveryRun;
use App\Models\Candidate;
use App\Technology\Detectors\WordPressDetector;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class DiscoverBusinesses implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public DiscoveryRun $discoveryRun
    ) {
    }

    public function handle(): void
    {

        // Update the discovery run status to 'running' and set the started_at timestamp
        $this->discoveryRun->update([
            'status' => 'running',
            'started_at' => now(),
        ]);

        // Simulate the discovery process (this is where you would implement the actual discovery logic)
        $detector = new WordPressDetector();

        // Dummy candidates for demonstration purposes
        $candidates = [
            [
                'name' => 'Example WordPress Business Ltd',
                'website' => 'https://wordpress.org',
                'domain' => 'wordpress.org',
                'location' => $this->discoveryRun->location,
                'category' => $this->discoveryRun->category,
                'source' => $this->discoveryRun->source,
                'status' => 'new',
            ],
            [
                'name' => 'Example Electrical Services',
                'website' => 'https://example.org',
                'domain' => 'example.org',
                'location' => $this->discoveryRun->location,
                'category' => $this->discoveryRun->category,
                'source' => $this->discoveryRun->source,
                'status' => 'new',
            ],
        ];

        // Process each candidate and detect if they are using WordPress
        foreach ($candidates as $candidate) {

            // Detect if the candidate's website is using WordPress
            $technology = $detector->detect($candidate['website']);

            // Add the is_wordpress field to the candidate data
            $candidate['is_wordpress'] = $technology?->name === 'WordPress';

            // Create the candidate record in the database
            $this->discoveryRun->candidates()->create($candidate);

        }

        $this->discoveryRun->update([
            'status' => 'completed',
            'candidates_found' => count($candidates),
            'completed_at' => now(),
        ]);
    }
}