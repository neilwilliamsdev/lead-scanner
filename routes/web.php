<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\ScanController;
use App\Http\Controllers\CandidateController;

Route::get('/', function () {
    return view('welcome');
});

// Business routes
Route::resource('businesses', BusinessController::class);

// Scan routes
Route::get('/scans/{scan}', [ScanController::class, 'show'])->name('scans.show');

// Candidate routes
Route::get('/candidates/{candidate}', [CandidateController::class, 'show'])->name('candidates.show');
Route::post('/candidates/{candidate}/accept', [CandidateController::class, 'accept'])->name('candidates.accept');