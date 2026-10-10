<?php

namespace App\Technology\Detectors;

use App\Technology\Technology;
use App\Technology\TechnologyDetector;

class Shopify implements TechnologyDetector
{
    public function detect(string $url, string $html): ?Technology
    {
        $html = strtolower($html);

        if (
            str_contains($html, 'cdn.shopify.com') ||
            str_contains($html, '/cdn/shop/')
        ) {
            return new Technology('Shopify');
        }

        return null;
    }
}