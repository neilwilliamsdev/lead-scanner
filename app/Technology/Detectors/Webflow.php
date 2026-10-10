<?php

namespace App\Technology\Detectors;

use App\Technology\Technology;
use App\Technology\TechnologyDetector;

class Webflow implements TechnologyDetector
{
    public function detect(string $url, string $html): ?Technology
    {
        $html = strtolower($html);

        if (
            str_contains($html, 'webflow')
        ) {
            return new Technology('Webflow');
        }

        return null;
    }
}