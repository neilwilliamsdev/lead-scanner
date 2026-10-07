<?php

namespace App\Technology\Detectors;

use App\Technology\Technology;
use App\Technology\TechnologyDetector;

class WordPress implements TechnologyDetector
{
    public function detect(string $url, string $html): ?Technology
    {
        $html = strtolower($html);

        if (
            str_contains($html, '/wp-content/') ||
            str_contains($html, '/wp-includes/')
        ) {
            return new Technology('WordPress');
        }

        return null;
    }
}