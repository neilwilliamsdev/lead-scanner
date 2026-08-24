<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Candidate;

class DiscoveryRun extends Model
{
    protected $fillable = [
        'source',
        'category',
        'location',
        'radius',
        'status',
        'candidates_found',
        'businesses_created',
        'started_at',
        'completed_at',
        'error',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Define relationship to candidate class
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\Candidate>
     */
    public function candidates()
    {
        return $this->hasMany(Candidate::class);
    }
}