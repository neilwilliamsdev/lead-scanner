<?php

namespace App\Technology\Detectors;

use App\Technology\Technology;
use App\Technology\TechnologyDetector;

class Wix implements TechnologyDetector
{
    public function detect(string $url, string $html): ?Technology
    {
        $html = strtolower($html);

        if (
            str_contains($html, 'static.wixstatic.com') ||
            str_contains($html, 'static.parastorage.com')
        ) {
            return new Technology('Wix');
        }

        return null;
    }
}