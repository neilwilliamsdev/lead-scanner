<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\ScanController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('businesses', BusinessController::class);
Route::get('/scans/{scan}', [ScanController::class, 'show'])->name('scans.show');