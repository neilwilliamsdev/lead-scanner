<?php

namespace App\Technology;

use Illuminate\Support\Facades\Http;

class TechnologyDetectorManager
{
    public function __construct(
        protected array $detectors
    ) {
    }

    public function detect(string $url): array
    {
        try {
            $response = Http::timeout(10)->get($url);

            if (! $response->successful()) {
                return [];
            }

            $html = $response->body();
        } catch (\Throwable) {
            return [];
        }

        $technologies = [];

        foreach ($this->detectors as $detector) {
            $technology = $detector->detect($url, $html);

            if ($technology) {
                $technologies[] = $technology;
            }
        }

        return $technologies;
    }
}