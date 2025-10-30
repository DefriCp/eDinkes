<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\VisitController;
use App\Http\Controllers\GeoRefController;
use App\Http\Controllers\FacilityApiController; 
use App\Http\Controllers\TenagaPydController;
use App\Http\Controllers\PosyanduController;

Route::get('/', fn () => redirect()->route('dashboard'));

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/gis',                [MapController::class, 'index'])->name('gis.index');
    Route::get('/gis/geojson',        [MapController::class, 'geojson'])->name('gis.geojson');
    Route::get('/gis/facilities',     [MapController::class, 'facilities'])->name('gis.facilities');
    Route::get('/gis/geo-posyandu',   [MapController::class,'geoPosyandu'])->name('gis.geo.posyandu');
    Route::get('/gis/geo-kader',      [MapController::class,'geoKader'])->name('gis.geo.kader');
    Route::get('/gis/points-posyandu',[MapController::class,'pointsPosyandu'])->name('gis.points.posyandu');
    Route::get('/gis/points-kader',   [MapController::class,'pointsKader'])->name('gis.points.kader');
    Route::resource('visits', VisitController::class)->parameters(['visits' => 'visit']);
    Route::get('visits-import',  [VisitController::class,'importForm'])->name('visits.import.form');
    Route::post('visits-import', [VisitController::class,'importStore'])->name('visits.import.store');
    Route::get('/ref/kecamatan', [GeoRefController::class, 'kecamatan'])->name('ref.kecamatan');
    Route::get('/ref/desa',      [GeoRefController::class, 'desa'])->name('ref.desa');
    Route::get('/api/facilities',[FacilityApiController::class, 'list'])->name('api.facilities');
    Route::resource('posyandu', PosyanduController::class);
    Route::get('posyandu-import',  [PosyanduController::class,'importForm'])->name('posyandu.import.form');
    Route::post('posyandu-import', [PosyanduController::class,'importStore'])->name('posyandu.import.store');
    Route::resource('tenagapyd', TenagaPydController::class);
    Route::get('tenagapyd-import',  [TenagaPydController::class,'importForm'])->name('tenagapyd.import.form');
    Route::post('tenagapyd-import', [TenagaPydController::class,'importStore'])->name('tenagapyd.import.store');
    Route::view('/units',   'units.index')->name('units.index');
    Route::view('/vehicles','vehicles.index')->name('vehicles.index');
    Route::view('/tenaga',  'tenaga.index')->name('tenaga.index');
});

require __DIR__ . '/auth.php';
