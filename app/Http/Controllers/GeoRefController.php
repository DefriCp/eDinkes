<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class GeoRefController extends Controller
{

    private function normalize(array $rows, array $idKeys, array $nameKeys): array
    {
        $out = [];
        foreach ($rows as $r) {
            if (!is_array($r)) continue;

            $id = null; $name = null;

            foreach ($idKeys as $k) {
                if (array_key_exists($k, $r) && $r[$k] !== null && $r[$k] !== '') { $id = (string)$r[$k]; break; }
            }
            foreach ($nameKeys as $k) {
                if (array_key_exists($k, $r) && $r[$k] !== null && $r[$k] !== '') { $name = (string)$r[$k]; break; }
            }

            if ($id !== null && $name !== null) {
                $out[] = ['id' => $id, 'text' => $name];
            }
        }
        return $out;
    }

    private function http(): \Illuminate\Http\Client\PendingRequest
    {
        // Aman TLS (tanpa verify=false), retry 3x dengan backoff 500ms
        return Http::timeout(15)
            ->acceptJson()
            ->retry(3, 500, throw: false);
    }

    private function saveLocal(string $path, array $data): void
    {
        Storage::disk('local')->put($path, json_encode($data, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
    }

    private function loadLocal(string $path): array
    {
        if (!Storage::disk('local')->exists($path)) return [];
        $json = json_decode(Storage::disk('local')->get($path), true);
        return is_array($json) ? $json : [];
    }

    /** ========= Endpoints ========= */

    // GET /ref/kecamatan?q=tasik
    public function kecamatan(Request $request)
    {
        $q = trim((string)$request->query('q', ''));

        try {
            $data = Cache::remember('ref_kecamatan_v2', 60*60*6, function () {
                $res = $this->http()->get('https://geoentry.tasikmalayakab.go.id/api/kecamatan');

                if ($res->successful()) {
                    $json = $res->json();
                    $rows = is_array($json) && array_key_exists('data', $json) ? $json['data'] : $json;

                    $norm = $this->normalize(
                        $rows,
                        ['id','id_kecamatan','kecamatan_id','kode','code','KODE','kode_bps'],
                        ['nama','nama_kecamatan','name','NAMA','label','kecamatan']
                    );

                    // simpan fallback lokal
                    $this->saveLocal('geo/kecamatan.json', $norm);
                    return $norm;
                }

                Log::warning('Kecamatan API failed', ['status' => $res->status(), 'body' => $res->body()]);
                $local = $this->loadLocal('geo/kecamatan.json');
                if (!empty($local)) return $local;

                abort(502, 'Gagal mengambil data kecamatan');
            });

            if ($q !== '') {
                $qq = mb_strtolower($q);
                $data = array_values(array_filter($data, fn($r) => str_contains(mb_strtolower($r['text']), $qq)));
            }

            return response()->json(['results' => $data]);
        } catch (\Throwable $e) {
            Log::error('Kecamatan proxy error', ['e' => $e->getMessage()]);
            return response()->json(['results' => []], 502);
        }
    }

    // GET /ref/desa?kecamatan_id=XXX&q=sukar
    public function desa(Request $request)
    {
        $kecId = $request->query('kecamatan_id');
        if (!$kecId) {
            return response()->json(['message' => 'kecamatan_id wajib diisi'], 400);
        }
        $q = trim((string)$request->query('q', ''));

        try {
            $cacheKey = 'ref_desa_v2_'.$kecId;

            $data = Cache::remember($cacheKey, 60*60*6, function () use ($kecId) {
                $query = [
                    'kecamatan_id'  => $kecId,
                    'id_kecamatan'  => $kecId,
                    'kecamatan'     => $kecId,
                ];

                $res = $this->http()->get('https://geoentry.tasikmalayakab.go.id/api/desa', $query);

                if ($res->successful()) {
                    $json = $res->json();
                    $rows = is_array($json) && array_key_exists('data', $json) ? $json['data'] : $json;

                    $norm = $this->normalize(
                        $rows,
                        ['id','id_desa','desa_id','kode','code','KODE','kode_bps'],
                        ['nama','nama_desa','name','NAMA','label','desa','kelurahan']
                    );

                    $this->saveLocal("geo/desa_{$kecId}.json", $norm);
                    return $norm;
                }

                Log::warning('Desa API failed', ['status' => $res->status(), 'body' => $res->body(), 'kec' => $kecId]);
                $local = $this->loadLocal("geo/desa_{$kecId}.json");
                if (!empty($local)) return $local;

                abort(502, 'Gagal mengambil data desa');
            });

            if ($q !== '') {
                $qq = mb_strtolower($q);
                $data = array_values(array_filter($data, fn($r) => str_contains(mb_strtolower($r['text']), $qq)));
            }

            return response()->json(['results' => $data]);
        } catch (\Throwable $e) {
            Log::error('Desa proxy error', ['e' => $e->getMessage(), 'kec' => $kecId]);
            return response()->json(['results' => []], 502);
        }
    }
}
