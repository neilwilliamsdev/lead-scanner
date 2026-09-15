<?php

namespace App\Website;

class AnalysisResult
{
    public function __construct(
        public string $check,
        public bool $passed,
        public ?string $message = null,
        public int $score = 0,
    ) {
    }
}