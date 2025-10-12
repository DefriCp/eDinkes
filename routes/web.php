<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\VisitController;
use App\Http\Controllers\GeoRefController;
use App\Http\Controllers\FacilityApiController; 
Route::get('/', fn () => redirect()->route('dashboard'));

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/gis', [MapController::class, 'index'])->name('gis.index');
    Route::get('/gis/geojson', [MapController::class, 'geojson']);
    Route::get('/gis/facilities', [MapController::class, 'facilities']);
    Route::resource('visits', VisitController::class)->parameters(['visits' => 'visit']);
    Route::get('/ref/kecamatan', [GeoRefController::class, 'kecamatan'])->name('ref.kecamatan');
    Route::get('/ref/desa',      [GeoRefController::class, 'desa'])->name('ref.desa');
    Route::get('/api/facilities', [FacilityApiController::class, 'list'])->name('api.facilities');
});

require __DIR__ . '/auth.php';
