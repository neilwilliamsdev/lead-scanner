<?php

namespace App\Jobs;

use App\Discovery\DiscoverySource;
use App\Models\Business;
use App\Models\DiscoveryRun;
use App\Models\Technology;
use App\Technology\Detectors\WordPressDetector;
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

    public function handle(DiscoverySource $source, WebsiteChecker $websiteChecker): void
    {

        // Update the discovery run status to 'running' and set the started_at timestamp
        $this->discoveryRun->update([
            'status' => 'running',
            'started_at' => now(),
        ]);

        // Create an instance of the WordPressDetector
        $detector = new WordPressDetector();

        // Use the discovery source to search for businesses based on the category and location
        $businesses = $source->search(
            $this->discoveryRun->category,
            $this->discoveryRun->location
        );

        foreach ($businesses as $businessData) {

            // Extract the domain from the website URL
            $domain = parse_url($businessData['website'], PHP_URL_HOST);

            // Create or update the business record in the database
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

            // Create a new candidate record associated with the discovery run and the business
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

            // Check if the website is reachable and update the candidate's website_reachable field
            $website = $websiteChecker->check($candidate->website);

            // Update the candidate's website_reachable field based on the result of the website check
            $candidate->update([
                'website_reachable' => $website['reachable'],
            ]);

            // Only attempt to detect the technology if the website is reachable
            if ($website['reachable']) {
                $technology = $detector->detect($candidate->website);

                if ($technology) {
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

        $this->discoveryRun->update([
            'status' => 'completed',
            'candidates_found' => count($businesses),
            'completed_at' => now(),
        ]);
    }
}