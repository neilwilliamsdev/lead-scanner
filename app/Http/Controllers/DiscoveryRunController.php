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
}