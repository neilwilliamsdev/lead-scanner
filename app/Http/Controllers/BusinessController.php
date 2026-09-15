<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Business;

class BusinessController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Retrieve all businesses ordered by latest
        $businesses = Business::latest()->get();

        // Pass the retrieved businesses to the view
        return view('businesses.index', compact('businesses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('businesses.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming request data
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'website' => ['required', 'url', 'max:255'],
            'industry' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'contact_name' => ['nullable', 'string', 'max:255'],
            'contact_email' => ['nullable', 'email', 'max:255'],
            'notes' => ['nullable', 'string'],
        ]);

        // Create a new business record with the validated data
        $business = Business::create($validated);

        // Redirect to the newly created business's detail page
        return redirect()->route('businesses.show', $business);
    }

    /**
     * Display the specified resource.
     */
    public function show(Business $business)
    {

        // Load the scans relationship for the business
        $business->load('scans');

        // Pass the business with its scans to the view
        return view('businesses.show', compact('business'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Business $business)
    {
        return view('businesses.edit', compact('business'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Business $business)
    {
        // Validate the incoming request data for updating the business
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'website' => ['required', 'url', 'max:255'],
            'industry' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'contact_name' => ['nullable', 'string', 'max:255'],
            'contact_email' => ['nullable', 'email', 'max:255'],
            'notes' => ['nullable', 'string'],
        ]);

        // Update the business record with the validated data
        $business->update($validated);

        // Redirect to the updated business's detail page
        return redirect()->route('businesses.show', $business);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Business $business)
    {
        // Delete the specified business record
        $business->delete();

        // Redirect to the list of businesses after deletion
        return redirect()->route('businesses.index');
    }
}
