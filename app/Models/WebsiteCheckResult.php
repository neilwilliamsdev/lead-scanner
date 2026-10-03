<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WebsiteCheckResult extends Model
{
    protected $fillable = [
        'check',
        'passed',
        'message',
        'score',
    ];

    protected function casts(): array
    {
        return [
            'passed' => 'boolean',
            'score' => 'integer',
        ];
    }

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }
}
