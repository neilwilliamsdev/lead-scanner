<?php

namespace App\Technology;

interface TechnologyDetector
{
    /**
     * Detect the technology used by a website given its URL and HTML content.
     *
     * @param string $url
     * @param string $html
     * @return Technology|null
     */
    public function detect(string $url, string $html): ?Technology;
}