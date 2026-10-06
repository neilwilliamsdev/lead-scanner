<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Business;
use App\Models\DiscoveryRun;

class Candidate extends Model
{
    protected $fillable = [
        'discovery_run_id',
        'business_id',
        'name',
        'website',
        'domain',
        'location',
        'category',
        'source',
        'source_id',
        'status',
        'website_reachable',
    ];

    protected $casts = [
        'website_reachable' => 'boolean',
    ];

    /**
     * Define relationship to discovery run class
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\DiscoveryRun>
     */
    public function discoveryRun()
    {
        return $this->belongsTo(DiscoveryRun::class);
    }

    /**
     * Define relationship to business class
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\Business>
     */
    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    /**
     * Calculates the overall website score from the scan results.
     *
     * @return int
     */
    public function score(): int
    {
        return $this->business?->score() ?? 0;
    }

    /**
     * Get the Lighthouse score for a specific category.
     *
     * @param string $category
     * @return integer|null
     */
    public function lighthouseCategoryScore(string $category): ?int
    {
        return $this->business?->lighthouseCategoryScore($category);
    }
}