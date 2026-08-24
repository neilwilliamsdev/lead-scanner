<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

    public function discoveryRun()
    {
        return $this->belongsTo(DiscoveryRun::class);
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }
}