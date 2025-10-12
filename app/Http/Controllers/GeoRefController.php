<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeoRefController extends Controller
{
    private function normalize(array $rows, array $idKeys, array $nameKeys): array
    {
        $out = [];
        foreach ($rows as $r) {
            if (!is_array($r)) { continue; }
            $id = null; $name = null;

            foreach ($idKeys as $k)  { if (array_key_exists($k, $r) && $r[$k] !== null && $r[$k] !== '') { $id = $r[$k]; break; } }
            foreach ($nameKeys as $k){ if (array_key_exists($k, $r) && $r[$k] !== null && $r[$k] !== '') { $name = $r[$k]; break; } }

            if ($id !== null && $name !== null) {
                $out[] = ['id' => $id, 'nama' => $name];
            }
        }
        return $out;
    }

    public function kecamatan()
    {
        try {
            $data = Cache::remember('ref_kecamatan', 60*60*6, function () {
                $res = Http::withOptions(['verify' => false]) 
                    ->timeout(15)
                    ->acceptJson()
                    ->get('https://geoentry.tasikmalayakab.go.id/api/kecamatan');

                if (!$res->successful()) {
                    Log::warning('Kecamatan API failed', ['status' => $res->status(), 'body' => $res->body()]);
                    abort(502, 'Gagal mengambil data kecamatan');
                }

                $json = $res->json();
                $rows = is_array($json) && array_key_exists('data', $json) ? $json['data'] : $json;

                return $this->normalize(
                    $rows,
                    ['id','id_kecamatan','kecamatan_id','kode','code','KODE','kode_bps'],
                    ['nama','nama_kecamatan','name','NAMA','label']
                );
            });

            return response()->json($data);
        } catch (\Throwable $e) {
            Log::error('Kecamatan proxy error', ['e' => $e->getMessage()]);
            return response()->json([], 502);
        }
    }

    public function desa(Request $request)
    {
        $kecId = $request->query('kecamatan_id');
        if (!$kecId) {
            return response()->json(['message' => 'kecamatan_id wajib diisi'], 400);
        }

        try {
            $cacheKey = 'ref_desa_'.$kecId;

            $data = Cache::remember($cacheKey, 60*60*6, function () use ($kecId) {
                $query = [
                    'kecamatan_id'  => $kecId,
                    'id_kecamatan'  => $kecId,
                    'kecamatan'     => $kecId,
                ];

                $res = Http::withOptions(['verify' => false])
                    ->timeout(15)
                    ->acceptJson()
                    ->get('https://geoentry.tasikmalayakab.go.id/api/desa', $query);

                if (!$res->successful()) {
                    Log::warning('Desa API failed', ['status' => $res->status(), 'body' => $res->body(), 'kec' => $kecId]);
                    abort(502, 'Gagal mengambil data desa');
                }

                $json = $res->json();
                $rows = is_array($json) && array_key_exists('data', $json) ? $json['data'] : $json;

                return $this->normalize(
                    $rows,
                    ['id','id_desa','desa_id','kode','code','KODE','kode_bps'],
                    ['nama','nama_desa','name','NAMA','label']
                );
            });

            return response()->json($data);
        } catch (\Throwable $e) {
            Log::error('Desa proxy error', ['e' => $e->getMessage(), 'kec' => $kecId]);
            return response()->json([], 502);
        }
    }
}
