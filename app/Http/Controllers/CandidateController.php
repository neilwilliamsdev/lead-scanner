<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Business;
use App\Models\Candidate;

class CandidateController extends Controller
{
    public function show(Candidate $candidate)
    {
        return view('candidates.show', compact('candidate'));
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
        $business = Business::create([
            'name' => $candidate->name,
            'website' => $candidate->website,
            'location' => $candidate->location,
            'industry' => $candidate->category,
        ]);

        $candidate->update([
            'status' => 'accepted',
            'business_id' => $business->id,
        ]);

        return redirect()->route('businesses.show', $business);
    }
}
