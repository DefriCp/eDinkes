<?php

namespace App\Imports;

use App\Models\Posyandu;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class PosyanduImport implements ToModel, WithHeadingRow, WithChunkReading, WithBatchInserts, SkipsEmptyRows
{
    public function model(array $row)
    {

        $row = collect($row)->keyBy(fn($v,$k)=>strtolower(trim($k)))->all();

        return new Posyandu([
            'id_posyandu' => $this->clean(Arr::get($row,'id_posyandu')),
            'provinsi'    => $this->clean(Arr::get($row,'provinsi')),
            'kabupaten'   => $this->clean(Arr::get($row,'kabupaten')),
            'kecamatan'   => $this->clean(Arr::get($row,'kecamatan')),
            'kode_desa'   => $this->clean(Arr::get($row,'kode_desa')),
            'desa'        => $this->clean(Arr::get($row,'desa')),

            'noregistrasi'   => $this->clean(Arr::get($row,'noregistrasi')),
            'nama_puskesmas' => $this->clean(Arr::get($row,'nama_puskesmas')),
            'nama_posyandu'  => $this->clean(Arr::get($row,'nama_posyandu')),

            'kriteria_1' => $this->clean(Arr::get($row,'kriteria_1')),
            'kriteria_2' => $this->clean(Arr::get($row,'kriteria_2')),
            'kriteria_3' => $this->clean(Arr::get($row,'kriteria_3')),
            'status_posyandu_aktif' => $this->clean(Arr::get($row,'status_posyandu_aktif')),

            'kriteria_siklus_hidup_1' => $this->clean(Arr::get($row,'kriteria_siklus_hidup_1')),
            'kriteria_siklus_hidup_2' => $this->clean(Arr::get($row,'kriteria_siklus_hidup_2')),
            'kriteria_siklus_hidup_3' => $this->clean(Arr::get($row,'kriteria_siklus_hidup_3')),
            'status_posyandu_siklus_hidup' => $this->clean(Arr::get($row,'status_posyandu_siklus_hidup')),
        ]);
    }

    public function chunkSize(): int { return 500; }
    public function batchSize(): int { return 500; }

    private function clean($v): ?string
    {
        if ($v === null) return null;
        $s = trim((string)$v);
        return $s === '' ? null : Str::limit($s, 255, '');
    }
}
