<?php

namespace App\Technology\Detectors;

use App\Technology\Technology;
use App\Technology\TechnologyDetector;
use Illuminate\Support\Facades\Http;

class WordPressDetector implements TechnologyDetector
{
    public function detect(string $url): ?Technology
    {
        try {
            $response = Http::timeout(10)->get($url);

            if (! $response->successful()) {
                return null;
            }

            $html = $response->body();

            if (
                str_contains($html, '/wp-content/') ||
                str_contains($html, '/wp-includes/')
            ) {
                return new Technology('WordPress');
            }

            return null;
        } catch (\Throwable) {
            return null;
        }
    }
}