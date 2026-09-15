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
    ];

    protected $casts = [
        'passed' => 'boolean',
    ];

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }
}