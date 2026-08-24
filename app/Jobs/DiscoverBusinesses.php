<?php

namespace App\Jobs;

use App\Models\DiscoveryRun;
use App\Models\Candidate;
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
        $this->discoveryRun->update([
            'status' => 'running',
            'started_at' => now(),
        ]);

        $candidates = [
            [
                'name' => 'Example Plumbing Ltd',
                'website' => 'https://example.com',
                'domain' => 'example.com',
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

        foreach ($candidates as $candidate) {
            $this->discoveryRun->candidates()->create($candidate);
        }

        $this->discoveryRun->update([
            'status' => 'completed',
            'candidates_found' => count($candidates),
            'completed_at' => now(),
        ]);
    }
}