<?php

namespace App\Discovery;

use Illuminate\Support\Facades\Http;

class GooglePlacesSource implements DiscoverySource
{
    public function search(string $category, string $location): array {

        // Use the Google Places API to search for places based on the category and location
        $response = Http::withHeaders([
            'X-Goog-Api-Key' => config('services.google.maps_api_key'),
            'X-Goog-FieldMask' => 'places.id,places.displayName,places.websiteUri',
        ])->post(
            'https://places.googleapis.com/v1/places:searchText',
            [
                'textQuery' => "{$category} in {$location}",
                'pageSize' => 10,
            ]
        );

        // Throw an exception if the request failed
        $response->throw();

        // Normalise the response to a common format for all discovery sources
        return collect($response->json('places', []))
            ->map(fn ($place) => [
                'source_id' => $place['id'],
                'name' => $place['displayName']['text'] ?? null,
                'website' => $place['websiteUri'] ?? null,
            ])
            ->filter(fn ($place) => $place['name'] && $place['website'] && $this->isWebsite($place['website']))
            ->values()
            ->all();
    }

    /**
     * Check if the given URL is a valid website and not a social media profile.
     *
     * @param string $url
     * @return boolean
     */
    private function isWebsite(string $url): bool
    {
        // Check if the URL is a valid website and not a social media profile
        $host = parse_url($url, PHP_URL_HOST);

        // Return true if the host is not null and not a social media domain
        return $host
            && ! in_array(
                strtolower($host),
                ['facebook.com', 'www.facebook.com', 'instagram.com', 'www.instagram.com'],
                true
            );
    }
}