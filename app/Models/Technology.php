<?php

namespace App\Models;
    
use App\Models\Candidate;
use Illuminate\Database\Eloquent\Model;

class Technology extends Model
{

    protected $fillable = [
        'name',
        'slug',
    ];
    
    /**
     * The candidates that belong to the technology.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<\App\Models\Candidate>
     */
    public function candidates()
    {
        return $this->belongsToMany(Candidate::class);
    }
}
