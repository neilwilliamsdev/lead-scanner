<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Scan;

class ScanController extends Controller
{
    public function show(Scan $scan)
    {
        return view('scans.show', compact('scan'));
    }
}
