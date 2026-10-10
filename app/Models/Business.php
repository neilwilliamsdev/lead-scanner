<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Scan;
use App\Models\Candidate;
use App\Models\Technology;
use App\Models\WebsiteCheckResult;
use App\Models\LighthouseResult;
use App\Models\Theme;

class Business extends Model
{
    protected $fillable = [
        'name',
        'website',
        'domain',
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

    /**
     * Define the technologies that belong to the business.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<\App\Models\Technology>
     */
    public function technologies()
    {
        return $this->belongsToMany(Technology::class);
    }

    /**
     * Define the themes that belong to the business.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<\App\Models\Theme>
     */
    public function themes()
    {
        return $this->belongsToMany(Theme::class);
    }

    /**
     * Define the website check results that belong to the business.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\WebsiteCheckResult>
     */
    public function websiteCheckResults()
    {
        return $this->hasMany(WebsiteCheckResult::class);
    }

    /**
     * Define the Lighthouse results that belong to the business.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\LighthouseResult>
     */
    public function lighthouseResults()
    {
        return $this->hasMany(LighthouseResult::class);
    }

    /**
     * Calculate the overall website score from the website checks.
     *
     * @return int
     */
    public function score(): int
    {
        return max(0, 100 + $this->websiteCheckResults->sum('score'));
    }

    /**
     * Get the Lighthouse score for a specific category.
     *
     * @param string $category
     * @return integer|null
     */
    public function lighthouseCategoryScore(string $category): ?int
    {
        $result = $this->lighthouseResults
            ->first(function ($result) use ($category) {
                return $result->check === 'Lighthouse: ' . $category;
            });

        if (! $result || ! isset($result->details['score'])) {
            return null;
        }

        return (int) round($result->details['score'] * 100);
    }

    /**
     * Get the CSS classes for the current status of the business.
     *
     * @return string
     */
    public function getStatusClassesAttribute() : string {

            return match ($this->status) {

                'new' => 'bg-gray-100 text-gray-700',
                'reviewing' => 'bg-blue-100 text-blue-800',
                'ready_to_contact' => 'bg-indigo-100 text-indigo-800',
                'contacted' => 'bg-purple-100 text-purple-800',
                'interested' => 'bg-green-100 text-green-800',
                'follow_up' => 'bg-amber-100 text-amber-800',
                'won' => 'bg-emerald-100 text-emerald-800',
                'lost' => 'bg-red-100 text-red-800',
                default => 'bg-gray-100 text-gray-700',

        };
    }
}
