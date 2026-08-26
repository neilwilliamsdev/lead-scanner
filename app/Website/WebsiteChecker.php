<?php

namespace App\Website;

use Illuminate\Support\Facades\Http;

class WebsiteChecker
{
    public function check(string $url): array
    {

        // Ensure the URL has a scheme (http or https)
        if (! preg_match('#^https?://#i', $url)) {
            $url = 'https://' . $url;
        }

        try {
            $response = Http::timeout(10)
                ->withHeaders([
                    'User-Agent' => 'Lead Scanner/1.0',
                ])
                ->get($url);

            return [
                'reachable' => true,
                'status' => $response->status(),
            ];
        } catch (\Throwable $e) {
            return [
                'reachable' => false,
                'status' => null,
                'error' => $e->getMessage(),
            ];
        }
    }
}