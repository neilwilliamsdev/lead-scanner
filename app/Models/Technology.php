<?php

namespace App\Models;
    
use App\Models\Business;
use Illuminate\Database\Eloquent\Model;

class Technology extends Model
{

    protected $fillable = [
        'name',
        'slug',
    ];
    
    /**
     * The businesses that belong to the technology.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<\App\Models\Business>
     */
    public function businesses()
    {
        return $this->belongsToMany(Business::class);
    }
}
