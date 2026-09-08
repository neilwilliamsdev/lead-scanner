<?php

namespace App\Technology;

class TechnologyDetectorManager
{
    public function __construct(
        protected array $detectors
    ) {
    }

    public function detect(string $url): array
    {
        $technologies = [];

        foreach ($this->detectors as $detector) {
            $technology = $detector->detect($url);

            if ($technology) {
                $technologies[] = $technology;
            }
        }

        return $technologies;
    }
}