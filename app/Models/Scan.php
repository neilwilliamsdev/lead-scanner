<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Scan extends Model
{

    // The attributes that are mass assignable.
    protected $fillable = [
        'business_id',
        'status',
        'score',
        'results',
        'started_at',
        'completed_at',
    ];

    // The attributes that should be cast to native types.
    protected $casts = [
        'results' => 'array',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Set relationship between scans and businesses
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function business()
    {
        return $this->belongsTo(Business::class);
    }
}
