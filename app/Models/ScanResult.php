<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScanResult extends Model
{
    protected $fillable = [
        'candidate_id',
        'check',
        'passed',
        'message',
        'score',
        'details',
    ];

    protected $casts = [
        'passed' => 'boolean',
        'details' => 'array',
    ];

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }
}