<?php

namespace App\Technology\Detectors;

use App\Technology\Technology;
use App\Technology\TechnologyDetector;

class WooCommerce implements TechnologyDetector
{
    public function detect(string $url, string $html): ?Technology
    {
        $html = strtolower($html);

        if (
            str_contains($html, '/wp-content/plugins/woocommerce/') ||
            str_contains($html, 'woocommerce-') ||
            str_contains($html, 'woocommerce/')
        ) {
            return new Technology('WooCommerce');
        }

        return null;
    }
}