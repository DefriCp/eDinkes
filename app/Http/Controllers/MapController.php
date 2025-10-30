<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use App\Models\AreaMetric;
use App\Models\HealthFacility;
use App\Models\Posyandu;
use App\Models\Pyd;

class MapController extends Controller
{
    public function index()
    {
        $month = (int) request('month', now()->month);
        $year  = (int) request('year',  now()->year);
        return view('gis.index', compact('month','year'));
    }

    /* ================= Helpers ================= */

    // Ambil polygon GeoJSON dari public/ atau storage/. Kalau tidak ada, return null
    private function tryLoadPolygon(): ?array
    {
        $pub = public_path('geo/kecamatan.geojson');
        if (file_exists($pub)) return json_decode(file_get_contents($pub), true);

        $path = 'geo/kecamatan.geojson';
        if (Storage::disk('public')->exists($path)) {
            return json_decode(Storage::disk('public')->get($path), true);
        }
        return null; // tidak ada polygon
    }

    // Normalisasi nama buat matching by-name
    private function norm(?string $s): ?string
    {
        if ($s === null) return null;
        $s = strtoupper(trim($s));
        $s = str_replace(['KEC.','KECAMATAN '], ['', ''], $s);
        return preg_replace('/[^A-Z0-9]/','',$s) ?: null;
    }

    // Ambil nama kecamatan dari berbagai kemungkinan key
    private function featureDistrictName(array $props): ?string
    {
        foreach (['nama','NAMA','kecamatan','KECAMATAN','WADMKC','NAMOBJ'] as $k) {
            if (!empty($props[$k])) return $props[$k];
        }
        return null;
    }

    // Sisipkan count ke polygon (by name)
    private function attachCounts(array &$geojson, array $counts, string $prop): void
    {
        foreach ($geojson['features'] as &$f) {
            $nm  = $this->featureDistrictName($f['properties'] ?? []);
            $key = $this->norm($nm);
            $val = $key && isset($counts[$key]) ? (int)$counts[$key] : 0;
            $f['properties'][$prop] = $val;
            $f['properties']['_nama_disp'] = $f['properties']['_nama_disp'] ?? ($nm ?: 'Kecamatan');
        }
    }

    // Ambil daftar kecamatan (dari API) untuk fallback titik
    private function fetchKecamatan(): array
    {
        return Cache::remember('gis:kecamatan_full', 60*60*6, function () {
            $res = Http::withOptions(['verify'=>false])->timeout(20)->acceptJson()
                ->get('https://geoentry.tasikmalayakab.go.id/api/kecamatan');
            if (!$res->successful()) abort(502,'Gagal ambil kecamatan');
            $json = $res->json();
            $rows = is_array($json) && array_key_exists('data',$json) ? $json['data'] : $json;

            $out=[];
            foreach ($rows as $r) {
                $nama = $r['nama'] ?? $r['nama_kecamatan'] ?? $r['name'] ?? $r['NAMA'] ?? null;
                $lat  = $r['lat'] ?? $r['latitude'] ?? null;
                $lng  = $r['lng'] ?? $r['longitude'] ?? null;
                if ($nama && $lat !== null && $lng !== null) {
                    $out[] = ['nama'=>$nama, 'lat'=>(float)$lat, 'lng'=>(float)$lng, '_norm'=>$this->norm($nama)];
                }
            }
            return $out;
        });
    }

    /* ========= Layer lama (IDL/K1/K4/DBD/Visits) pakai polygon kalau ada ========= */
    public function geojson(Request $req)
    {
        $month = (int)$req->query('month', now()->month);
        $year  = (int)$req->query('year',  now()->year);
        $json  = $this->tryLoadPolygon() ?: ['type'=>'FeatureCollection','features'=>[]];

        $metrics = AreaMetric::with('district')->where(compact('month','year'))->get()
            ->keyBy(fn($m)=>$m->district->code_bps);

        foreach ($json['features'] as &$f) {
            $code = $f['properties']['kode_bps'] ?? $f['properties']['KODE_BPS'] ?? null;
            if ($code && $metrics->has($code)) {
                $m = $metrics[$code];
                $f['properties']['idl_pct']   = (float)$m->idl_pct;
                $f['properties']['k1_pct']    = (float)$m->k1_pct;
                $f['properties']['k4_pct']    = (float)$m->k4_pct;
                $f['properties']['dbd_cases'] = (int)$m->dbd_cases;
                $f['properties']['visits']    = (int)$m->visits;
            } else {
                $f['properties']['idl_pct'] = $f['properties']['k1_pct'] = $f['properties']['k4_pct'] = null;
                $f['properties']['dbd_cases'] = 0; $f['properties']['visits']=0;
            }
            $f['properties']['_nama_disp'] = $f['properties']['_nama_disp'] ?? ($this->featureDistrictName($f['properties']) ?? 'Kecamatan');
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

    /* ========= POSYANDU & KADER – POLYGON ========= */
    public function geoPosyandu()
    {
        $json = $this->tryLoadPolygon();
        if (!$json) abort(404); // biar view fallback ke titik

        $counts = Posyandu::selectRaw('UPPER(kecamatan) as kec, COUNT(*) as total')
            ->whereNotNull('kecamatan')->groupBy('kec')->pluck('total','kec')->toArray();

        $norm=[]; foreach ($counts as $k=>$v) $norm[$this->norm($k)] = (int)$v;
        $this->attachCounts($json, $norm, 'posyandu_count');

        return response()->json($json);
    }

    public function geoKader()
    {
        $json = $this->tryLoadPolygon();
        if (!$json) abort(404);

        $counts = Pyd::selectRaw('UPPER(kecamatan) as kec, COUNT(*) as total')
            ->whereNotNull('kecamatan')->groupBy('kec')->pluck('total','kec')->toArray();

        $norm=[]; foreach ($counts as $k=>$v) $norm[$this->norm($k)] = (int)$v;
        $this->attachCounts($json, $norm, 'kader_count');

        return response()->json($json);
    }

    /* ========= POSYANDU & KADER – TITIK (fallback) ========= */
    public function pointsPosyandu()
    {
        $kec = $this->fetchKecamatan();
        $counts = Posyandu::selectRaw('UPPER(kecamatan) as kec, COUNT(*) as total')
            ->whereNotNull('kecamatan')->groupBy('kec')->pluck('total','kec')->toArray();

        $features=[];
        foreach ($kec as $r) {
            $features[] = [
                'type'=>'Feature',
                'geometry'=>['type'=>'Point','coordinates'=>[$r['lng'],$r['lat']]],
                'properties'=>['nama'=>$r['nama'],'posyandu_count'=>(int)($counts[$r['_norm']] ?? 0)]
            ];
        }
        return response()->json(['type'=>'FeatureCollection','features'=>$features]);
    }

    public function pointsKader()
    {
        $kec = $this->fetchKecamatan();
        $counts = Pyd::selectRaw('UPPER(kecamatan) as kec, COUNT(*) as total')
            ->whereNotNull('kecamatan')->groupBy('kec')->pluck('total','kec')->toArray();

        $features=[];
        foreach ($kec as $r) {
            $features[] = [
                'type'=>'Feature',
                'geometry'=>['type'=>'Point','coordinates'=>[$r['lng'],$r['lat']]],
                'properties'=>['nama'=>$r['nama'],'kader_count'=>(int)($counts[$r['_norm']] ?? 0)]
            ];
        }
        return response()->json(['type'=>'FeatureCollection','features'=>$features]);
    }
}
