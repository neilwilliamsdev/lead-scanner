<?php
/**
 * Squarespace technology detector.
 */

namespace App\Technology\Detectors;

use App\Technology\Technology;
use App\Technology\TechnologyDetector;

class Squarespace implements TechnologyDetector
{
    public function detect(string $url, string $html): ?Technology
    {
        $html = strtolower($html);

        if (
            str_contains($html, 'static1.squarespace.com') ||
            str_contains($html, 'squarespace.com/api/1')
        ) {
            return new Technology('Squarespace');
        }

        return null;
    }
}