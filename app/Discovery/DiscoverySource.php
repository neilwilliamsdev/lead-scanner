<?php

namespace App\Discovery;

interface DiscoverySource
{
    public function search(
        string $category,
        string $location
    ): array;
}