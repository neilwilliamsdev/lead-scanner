<?php

namespace App\Technology;

interface TechnologyDetector
{
    public function detect(string $url): ?Technology;
}