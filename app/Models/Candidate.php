<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Business;
use App\Models\DiscoveryRun;
use App\Models\Technology;
use App\Models\WebsiteCheckResult;
use App\Models\LighthouseResult;

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
     * Define the technologies that belong to the candidate.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<\App\Models\Technology>
     */
    public function technologies()
    {
        return $this->belongsToMany(Technology::class);
    }

    /**
     * Define the website check results that belong to the candidate.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\WebsiteCheckResult>
     */
    public function websiteCheckResults()
    {
        return $this->hasMany(WebsiteCheckResult::class);
    }

    /**
     * Define the lighthouse results that belong to the candidate.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\LighthouseResult>
     */
    public function lighthouseResults()
    {
        return $this->hasMany(LighthouseResult::class);
    }

    /**
     * Calculates the overall website score from the scan results.
     *
     * @return int
     */
    public function score(): int
    {
        return max(0, 100 + $this->websiteCheckResults->sum('score'));
    }
}