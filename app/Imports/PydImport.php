<?php

namespace App\Imports;

use App\Models\Pyd;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class PydImport implements
    ToModel,
    WithHeadingRow,
    WithChunkReading,
    WithBatchInserts,
    SkipsEmptyRows
{
    public function model(array $row)
    {
        // normalisasi header -> lowercase, trim
        $row = collect($row)->keyBy(fn($v,$k) => strtolower(trim($k)))->all();

        return new Pyd([
            'provinsi'  => Arr::get($row,'provinsi'),
            'kabupaten' => Arr::get($row,'kabupaten'),
            'kecamatan' => Arr::get($row,'kecamatan'),
            'noregistrasi' => Arr::get($row,'noregistrasi'),
            'nama_puskesmas' => Arr::get($row,'nama_puskesmas'),
            'nama_posyandu'  => Arr::get($row,'nama_posyandu'),
            'nama_kader'     => Arr::get($row,'nama_kader'),
            'usia'           => $this->toInt(Arr::get($row,'usia')),
            'jenis_kelamin'  => $this->jk(Arr::get($row,'jenis_kelamin')),
            'nomor_sk'       => Arr::get($row,'nomor_sk'),
            'jenjang_pendidikan' => Arr::get($row,'jenjang_pendidikan'),
            'alamat_ktp'         => Arr::get($row,'alamat_ktp'),
            'alamat_domisili'    => Arr::get($row,'alamat_domisili'),
            'kepemilikan_jkn'    => Arr::get($row,'kepemilikan_jkn'),
            'metode_koordinasi'  => Arr::get($row,'metode_koordinasi'),

            'status_pelatihan_posyandu'    => Arr::get($row,'status_pelatihan_posyandu'),
            'status_penilaian_posyandu'    => Arr::get($row,'status_penilaian_posyandu'),
            'status_keterampilan_posyandu' => Arr::get($row,'status_keterampilan_posyandu'),

            'status_pelatihan_bayi_balita'    => Arr::get($row,'status_pelatihan_bayi_balita'),
            'status_penilaian_bayi_balita'    => Arr::get($row,'status_penilaian_bayi_balita'),
            'status_keterampilan_bayi_balita' => Arr::get($row,'status_keterampilan_bayi_balita'),

            'status_pelatihan_ibu_hamil'    => Arr::get($row,'status_pelatihan_ibu_hamil'),
            'status_penilaian_ibu_hamil'    => Arr::get($row,'status_penilaian_ibu_hamil'),
            'status_keterampilan_ibu_hamil' => Arr::get($row,'status_keterampilan_ibu_hamil'),

            'status_pelatihan_remaja'    => Arr::get($row,'status_pelatihan_remaja'),
            'status_penilaian_remaja'    => Arr::get($row,'status_penilaian_remaja'),
            'status_keterampilan_remaja' => Arr::get($row,'status_keterampilan_remaja'),

            'status_pelatihan_lansia'    => Arr::get($row,'status_pelatihan_lansia'),
            'status_penilaian_lansia'    => Arr::get($row,'status_penilaian_lansia'),
            'status_keterampilan_lansia' => Arr::get($row,'status_keterampilan_lansia'),

            'status_pelatihan_timbang_ukur' => Arr::get($row,'status_pelatihan_timbang_ukur'),
            'status_penilaian_timbang_ukur' => Arr::get($row,'status_penilaian_timbang_ukur'),

            'tingkatan_kader' => Arr::get($row,'tingkatan_kader'),
            'sudah_mengikuti_25_keterampilan_dasar' => $this->boolish(Arr::get($row,'sudah_mengikuti_25_keterampilan_dasar')),
            'sudah_dinilai_keterampilan_dasar'      => $this->boolish(Arr::get($row,'sudah_dinilai_keterampilan_dasar')),
        ]);
    }

    public function chunkSize(): int { return 500; }
    public function batchSize(): int { return 500; }

    private function toInt($v): ?int
    {
        $s = trim((string)$v);
        return is_numeric($s) ? (int)$s : null;
    }

    private function jk($v): ?string
    {
        if ($v === null) return null;
        $s = Str::upper(trim((string)$v));
        $s = str_replace(['.',',',';','-','_'], ' ', $s);
        $s = preg_replace('/\s+/', ' ', $s);
        if ($s === 'L' || Str::startsWith($s,'LAKI')) return 'L';
        if ($s === 'P' || Str::startsWith($s,'PEREM')) return 'P';
        if (in_array($s, ['M','MALE'])) return 'L';
        if (in_array($s, ['F','FEMALE'])) return 'P';
        return null;
    }

    private function boolish($v): bool
    {
        if (is_bool($v)) return $v;
        $s = Str::lower(trim((string)$v));
        return in_array($s, ['1','true','ya','y','yes','sudah','sudah ikut','sudah dinilai'], true);
    }
}
