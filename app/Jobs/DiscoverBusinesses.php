<?php

namespace App\Jobs;

use App\Discovery\DiscoverySource;
use App\Models\Business;
use App\Models\DiscoveryRun;
use App\Models\Technology;
use App\Technology\TechnologyDetectorManager;
use App\Website\WebsiteChecker;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Str;

class DiscoverBusinesses implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public DiscoveryRun $discoveryRun
    ) {
    }

    public function handle(
        DiscoverySource $source,
        WebsiteChecker $websiteChecker,
        TechnologyDetectorManager $technologyDetectorManager
    ): void {
        // Update the discovery run status to 'running' and set the started_at timestamp
        $this->discoveryRun->update([
            'status' => 'running',
            'started_at' => now(),
        ]);

        // Use the discovery source to search for businesses
        $businesses = $source->search(
            $this->discoveryRun->category,
            $this->discoveryRun->location
        );

        foreach ($businesses as $businessData) {

            // Extract the domain from the website URL
            $domain = parse_url($businessData['website'], PHP_URL_HOST);

            // Create the business if it doesn't already exist
            $business = Business::firstOrCreate(
                [
                    'domain' => $domain,
                ],
                [
                    'name' => $businessData['name'],
                    'website' => $businessData['website'],
                    'industry' => $this->discoveryRun->category,
                    'location' => $this->discoveryRun->location,
                ]
            );

            // Create a candidate associated with this discovery run and business
            $candidate = $this->discoveryRun->candidates()->create([
                'business_id' => $business->id,
                'name' => $businessData['name'],
                'website' => $businessData['website'],
                'domain' => $domain,
                'location' => $this->discoveryRun->location,
                'category' => $this->discoveryRun->category,
                'source' => $this->discoveryRun->source,
                'source_id' => $businessData['source_id'],
                'status' => 'new',
            ]);

            // Check whether the website is reachable
            $website = $websiteChecker->check($candidate->website);

            $candidate->update([
                'website_reachable' => $website['reachable'],
            ]);

            // Detect technologies if the website is reachable
            if ($website['reachable']) {
                $technologies = $technologyDetectorManager->detect(
                    $candidate->website
                );

                foreach ($technologies as $technology) {
                    $technologyModel = Technology::firstOrCreate(
                        [
                            'slug' => Str::slug($technology->name),
                        ],
                        [
                            'name' => $technology->name,
                        ]
                    );

                    $candidate->technologies()->attach($technologyModel);
                }
            }
        }

        // Mark the discovery run as completed
        $this->discoveryRun->update([
            'status' => 'completed',
            'candidates_found' => count($businesses),
            'completed_at' => now(),
        ]);
    }
}