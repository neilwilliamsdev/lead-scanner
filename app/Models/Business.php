<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Scan;

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

}
