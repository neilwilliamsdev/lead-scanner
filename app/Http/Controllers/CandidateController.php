<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Business;
use App\Models\Candidate;
use App\Jobs\RunLighthouseAudit;

class CandidateController extends Controller
{
    public function show(Candidate $candidate)
    {
        return view('candidates.show', compact('candidate'));
    }

    public function index(Request $request)
    {
        $query = Candidate::where('status', 'new');

        if ($request->filled('location')) {
            $query->where('location', $request->location);
        }

        if ($request->filled('technology')) {
            $query->whereHas('business.technologies', function ($technologyQuery) use ($request) {
                $technologyQuery->where('technologies.id', $request->technology);
            });
        }

        $candidates = $query->latest()->get();

        $locations = Candidate::where('status', 'new')
            ->whereNotNull('location')
            ->where('location', '!=', '')
            ->distinct()
            ->orderBy('location')
            ->pluck('location');

        $technologies = \App\Models\Technology::orderBy('name')->get();

        return view('candidates.index', compact('candidates', 'locations', 'technologies'));
    }

    /**
     * Accept candidate as a business target, create business entry
     * and update candidate entry to reflect that change
     *
     * @param Candidate $candidate
     * @return \Illuminate\Http\RedirectResponse
     */
    public function accept(Candidate $candidate)
    {
        // Retrieve the associated business for the candidate
        $business = $candidate->business;

        // Update business status
        $candidate->update([
            'status' => 'accepted',
        ]);

        // Redirect to the associated business page
        return redirect()->route('businesses.show', $business);
    }

    /**
     * Rejects a candidate.
     *
     * @param Candidate $candidate
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reject(Candidate $candidate)
    {
        $candidate->update([
            'status' => 'rejected',
        ]);

        return redirect()->back();
    }
}
