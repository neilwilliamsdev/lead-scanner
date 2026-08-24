<?php

namespace App\Http\Controllers;

use App\Models\DiscoveryRun;

class DiscoveryRunController extends Controller
{
    public function index()
    {
        $discoveryRuns = DiscoveryRun::latest()->get();

        return view('discovery-runs.index', compact('discoveryRuns'));
    }

    public function show(DiscoveryRun $discoveryRun)
    {
        $discoveryRun->load('candidates');

        return view('discovery-runs.show', compact('discoveryRun'));
    }
}