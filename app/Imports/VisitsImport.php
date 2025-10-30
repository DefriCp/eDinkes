<?php

namespace App\Imports;

use App\Models\Visit;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Validators\Failure;

class VisitsImport implements ToCollection, WithHeadingRow, WithChunkReading, SkipsOnFailure
{
    protected array $ctx;
    protected int $success = 0;
    protected int $failed  = 0;
    protected array $errors = [];

    public function __construct(array $context) { $this->ctx = $context; }
    public function headingRow(): int { return 1; }
    public function chunkSize(): int { return 500; }
    public function getReport(): array { return ['success'=>$this->success,'failed'=>$this->failed,'errors'=>$this->errors]; }

    public function onFailure(Failure ...$failures)
    {
        foreach ($failures as $f) {
            $this->failed++;
            $this->errors[] = "Baris {$f->row()}: ".implode(', ', $f->errors());
        }
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $i => $row) {
            try {
                // Normalisasi: ganti string '?' / '-' / '' menjadi null
                $row = $row->map(function ($v) {
                    if (is_string($v)) {
                        $t = trim($v);
                        if ($t === '' || $t === '?' || $t === '-' || Str::startsWith($t, '-- ')) return null;
                    }
                    return $v;
                });

                $data = $this->mapRow($row);
                $data['facility_type'] = $this->ctx['facility_type'];
                $data['facility_id']   = $this->ctx['facility_id'];

                Visit::create($data);
                $this->success++;
            } catch (\Throwable $e) {
                $this->failed++;
                $this->errors[] = "Baris ".($i+2).": ".$e->getMessage();
            }
        }
    }

    protected function mapRow($r): array
    {
        $get = function(array $keys) use ($r) {
            foreach ($keys as $k) if (isset($r[$k]) && $r[$k] !== '') return $r[$k];
            return null;
        };

        $tanggal         = $get(['tanggal']);
        $nama            = $get(['nama pasien','nama_pasien','nama']);
        $noerm           = $get(['no erm','no eRM','no_erm','noerm']);
        $nik             = $get(['nik']);
        $no_rm_lama      = $get(['no. rm lama','no rm lama','no_rm_lama']);
        $no_dok_rm       = $get(['no. dokumen rm','no dokumen rm','no_dokumen_rm']);
        $jk              = $get(['jenis kelamin','jenis_kelamin','jk']);
        $tmplahir_tgllhr = $get(['tempat & tgl.lahir','tempat & tgl lahir','tempat_tgl_lahir']);
        $umurRaw         = $get(['umur']);
        $pekerjaan       = $get(['pekerjaan']);
        $alamat          = $get(['alamat']);
        $agama           = $get(['agama']);
        $status_nikah    = $get(['status pernikahan','status_pernikahan']);
        $kelurahan       = $get(['kelurahan','desa','desa/kelurahan']);
        $nama_ayah       = $get(['nama ayah','nama_ayah']);
        $jenis_kunjungan = $get(['jenis kunjungan','jenis_kunjungan']);
        $kunjungan       = $get(['kunjungan']);
        $poli            = $get(['poli/ruangan','poli','ruangan']);
        $asuransi        = $get(['asuransi']);
        $no_asuransi     = $get(['no. asuransi','no asuransi','no_asuransi']);
        $kode_diagnosa   = $get(['kode diagnosa','kode_diagnosa']);
        $diagnosa        = $get(['diagnosa']);
        $jenis_kasus     = $get(['jenis kasus','jenis_kasus']);

        $tanggal = $this->parseDate($tanggal);
        [$tempat_lahir, $tanggal_lahir] = $this->splitTempatTgl($tmplahir_tgllhr);
        $jenis_kelamin = $this->mapGender($jk);

        $data = [
            'tanggal'           => $tanggal,
            'nama_pasien'       => $nama,
            'no_erm'            => $noerm,
            'nik'               => $nik,
            'no_rm_lama'        => $no_rm_lama,
            'no_dokumen_rm'     => $no_dok_rm,
            'jenis_kelamin'     => $jenis_kelamin,
            'tempat_lahir'      => $tempat_lahir,
            'tanggal_lahir'     => $tanggal_lahir,
            'umur'              => $this->normalizeAge($umurRaw, $tanggal_lahir, $tanggal),
            'pekerjaan'         => $pekerjaan,
            'alamat'            => $alamat,
            'agama'             => $agama,
            'status_pernikahan' => $status_nikah,
            'kecamatan_id'      => null,
            'kecamatan_nama'    => null,
            'desa_id'           => null,
            'desa_nama'         => $kelurahan,
            'nama_ayah'         => $nama_ayah,
            'jenis_kunjungan'   => $jenis_kunjungan,
            'kunjungan'         => $kunjungan,
            'poli'              => $poli,
            'asuransi'          => $asuransi,
            'no_asuransi'       => $no_asuransi,
            'diagnosa'          => $diagnosa,
            'jenis_kasus'       => $jenis_kasus,
        ];

        if (Schema::hasColumn('visits', 'kode_diagnosa')) {
            $data['kode_diagnosa'] = $kode_diagnosa;
        }

        if (empty($data['tanggal']))      throw new \Exception('Tanggal kosong/format tidak dikenali');
        if (empty($data['nama_pasien']))  throw new \Exception('Nama pasien kosong');

        return $data;
    }

    protected function parseDate($v): ?string
    {
        if (!$v) return null;
        $try = ['Y-m-d','d/m/Y','d-m-Y','d.m.Y','m/d/Y','d M Y','d F Y','Y/m/d','Y.m.d'];
        foreach ($try as $fmt) {
            try { return Carbon::createFromFormat($fmt, trim((string)$v))->format('Y-m-d'); }
            catch (\Throwable $e) {}
        }
        if (is_numeric($v)) {
            try { return Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($v))->format('Y-m-d'); }
            catch (\Throwable $e) {}
        }
        try { return Carbon::parse($v)->format('Y-m-d'); } catch (\Throwable $e) { return null; }
    }

    protected function splitTempatTgl($v): array
    {
        if (!$v) return [null,null];
        $parts = explode(',', $v);
        $tempat = trim($parts[0]);
        $tgl = isset($parts[1]) ? $this->parseDate(trim($parts[1])) : null;
        return [$tempat ?: null, $tgl];
    }

    protected function mapGender($v): ?string
    {
        if (!$v) return null;
        $s = strtoupper(trim($v));
        if (in_array($s, ['L','LAKI','LAKI-LAKI','LAKI LAKI'])) return 'L';
        if (in_array($s, ['P','PR','PEREMPUAN'])) return 'P';
        return null;
    }

    protected function normalizeAge($umurRaw, ?string $dob, ?string $visitDate): ?int
    {
        // Jika kosong, coba dari tanggal lahir
        if ($umurRaw === null || $umurRaw === '') {
            if ($dob) {
                try {
                    $visit = $visitDate ? Carbon::parse($visitDate) : now();
                    $age = Carbon::parse($dob)->diffInYears($visit);
                    return ($age >= 0 && $age <= 150) ? $age : null;
                } catch (\Throwable $e) { return null; }
            }
            return null;
        }

        // Ambil angka
        $n = (int) filter_var($umurRaw, FILTER_SANITIZE_NUMBER_INT);
        if ($n >= 0 && $n <= 150) return $n;

        // Deteksi ddmmyy (6 digit) → hitung usia
        $s = (string) $n;
        if (strlen($s) === 6) {
            $d = substr($s, 0, 2);
            $m = substr($s, 2, 2);
            $y = substr($s, 4, 2);
            $currentYY = (int) date('y');
            $century = ((int)$y <= $currentYY) ? 2000 : 1900;
            $dobGuess = sprintf('%04d-%02d-%02d', $century + (int)$y, (int)$m, (int)$d);
            try {
                $visit = $visitDate ? Carbon::parse($visitDate) : now();
                $age = Carbon::parse($dobGuess)->diffInYears($visit);
                return ($age >= 0 && $age <= 150) ? $age : null;
            } catch (\Throwable $e) {}
        }

        return null;
    }
}
