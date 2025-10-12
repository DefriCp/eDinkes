<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\AreaMetric;
use App\Models\HealthFacility;

class MapController extends Controller
{
    public function index()
    {
        $month = (int) request('month', now()->month);
        $year  = (int) request('year',  now()->year);
        return view('gis.index', compact('month','year'));
    }

    public function geojson(Request $req)
    {
        $month = (int) $req->query('month', now()->month);
        $year  = (int) $req->query('year',  now()->year);

        $path = 'geo/kecamatan.geojson';
        if (!Storage::disk('public')->exists($path)) {
            return response()->json(['error' => 'GeoJSON not found'], 404);
        }

        $json = json_decode(Storage::disk('public')->get($path), true);

        $metrics = AreaMetric::with('district')
            ->where(compact('month','year'))
            ->get()
            ->keyBy(fn($m) => $m->district->code_bps);

        foreach ($json['features'] as &$f) {
            $code = $f['properties']['kode_bps'] ?? $f['properties']['KODE_BPS'] ?? null;

            if ($code && $metrics->has($code)) {
                $m = $metrics[$code];
                $f['properties']['idl_pct']   = (float) $m->idl_pct;
                $f['properties']['k1_pct']    = (float) $m->k1_pct;
                $f['properties']['k4_pct']    = (float) $m->k4_pct;
                $f['properties']['dbd_cases'] = (int) $m->dbd_cases;
                $f['properties']['visits']    = (int) $m->visits;
            } else {
                $f['properties']['idl_pct'] = $f['properties']['k1_pct'] = $f['properties']['k4_pct'] = null;
                $f['properties']['dbd_cases'] = 0;
                $f['properties']['visits'] = 0;
            }
        }

        return response()->json($json);
    }

    public function facilities()
    {
        return response()->json(
            HealthFacility::select('id','name','type','address','lat','lng')
                ->whereNotNull('lat')->whereNotNull('lng')->get()
        );
    }
}
