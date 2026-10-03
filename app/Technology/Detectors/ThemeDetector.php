<?php

namespace App\Technology\Detectors;

use Illuminate\Support\Facades\Http;

class ThemeDetector
{
    public function detect(string $url): ?string
    {
        try {
            $response = Http::timeout(10)->get($url);

            if (! $response->successful()) {
                return null;
            }

            $html = $response->body();

            if (preg_match('~/wp-content/themes/([^/"\'?]+)~i', $html, $matches)) {
                return $matches[1];
            }

            return null;
        } catch (\Throwable) {
            return null;
        }
    }
}