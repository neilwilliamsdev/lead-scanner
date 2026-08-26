<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Business;
use App\Models\DiscoveryRun;
use App\Models\Technology;

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
        'is_wordpress',
    ];

    protected $casts = [
        'website_reachable' => 'boolean',
        'is_wordpress' => 'boolean',
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
}