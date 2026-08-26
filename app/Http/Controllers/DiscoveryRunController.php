<?php

namespace App\Http\Controllers;

use App\Models\DiscoveryRun;
use App\Jobs\DiscoverBusinesses;
use Illuminate\Http\Request;

class DiscoveryRunController extends Controller
{
    public function index()
    {
        $discoveryRuns = DiscoveryRun::latest()->get();

        return view('discovery-runs.index', compact('discoveryRuns'));
    }

    public function create()
    {
        return view('discovery-runs.create');
    }

    public function show(DiscoveryRun $discoveryRun)
    {
        $discoveryRun->load('candidates');

        return view('discovery-runs.show', compact('discoveryRun'));
    }

    public function status(DiscoveryRun $discoveryRun)
    {
        return response()->json([
            'status' => $discoveryRun->status,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'source' => ['required', 'string'],
            'category' => ['required', 'string'],
            'location' => ['required', 'string'],
        ]);

        $discoveryRun = DiscoveryRun::create([
            ...$validated,
            'status' => 'pending',
        ]);

        DiscoverBusinesses::dispatch($discoveryRun);

        return redirect()->route('discovery-runs.show', $discoveryRun);
    }
}