<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\ScanController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\DiscoveryRunController; 

Route::get('/', function () {
    return view('welcome');
});

// Business routes
Route::resource('businesses', BusinessController::class);

// Scan routes
Route::get('/scans/{scan}', [ScanController::class, 'show'])->name('scans.show');

// Candidate routes
Route::get('/candidates', [CandidateController::class, 'index'])->name('candidates.index');
Route::get('/candidates/{candidate}', [CandidateController::class, 'show'])->name('candidates.show');
Route::post('/candidates/{candidate}/accept', [CandidateController::class, 'accept'])->name('candidates.accept');

// Discovery Run routes
Route::get('/discovery-runs', [DiscoveryRunController::class, 'index'])->name('discovery-runs.index');
Route::get('/discovery-runs/create', [DiscoveryRunController::class, 'create'])->name('discovery-runs.create');
Route::get('/discovery-runs/{discoveryRun}/status', [DiscoveryRunController::class, 'status'])->name('discovery-runs.status');
Route::get('/discovery-runs/{discoveryRun}', [DiscoveryRunController::class, 'show'])->name('discovery-runs.show');
Route::post('/discovery-runs', [DiscoveryRunController::class, 'store'])->name('discovery-runs.store');