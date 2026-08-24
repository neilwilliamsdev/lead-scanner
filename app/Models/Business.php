<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Scan;
use App\Models\Candidate;

class Business extends Model
{
    protected $fillable = [
        'name',
        'website',
        'industry',
        'location',
        'contact_name',
        'contact_email',
        'status',
        'notes',
    ];

    /**
     * Set relationship between scans and businesses
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\Scan>
     */
    public function scans()
    {
        return $this->hasMany(Scan::class);
    }

    /**
     * Set relationship between candidates and businesses
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\Candidate>
     */
    public function candidates()
    {
        return $this->hasMany(Candidate::class);
    }
}
