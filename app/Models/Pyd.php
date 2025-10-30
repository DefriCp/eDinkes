<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pyd extends Model
{
    protected $table = 'pyds';

    protected $fillable = [
        'provinsi','kabupaten','kecamatan','noregistrasi','nama_puskesmas','nama_posyandu',
        'nama_kader','usia','jenis_kelamin','nomor_sk','jenjang_pendidikan','alamat_ktp',
        'alamat_domisili','kepemilikan_jkn','metode_koordinasi',
        'status_pelatihan_posyandu','status_penilaian_posyandu','status_keterampilan_posyandu',
        'status_pelatihan_bayi_balita','status_penilaian_bayi_balita','status_keterampilan_bayi_balita',
        'status_pelatihan_ibu_hamil','status_penilaian_ibu_hamil','status_keterampilan_ibu_hamil',
        'status_pelatihan_remaja','status_penilaian_remaja','status_keterampilan_remaja',
        'status_pelatihan_lansia','status_penilaian_lansia','status_keterampilan_lansia',
        'status_pelatihan_timbang_ukur','status_penilaian_timbang_ukur',
        'tingkatan_kader','sudah_mengikuti_25_keterampilan_dasar','sudah_dinilai_keterampilan_dasar'
    ];

    protected $casts = [
        'usia' => 'integer',
        'sudah_mengikuti_25_keterampilan_dasar' => 'boolean',
        'sudah_dinilai_keterampilan_dasar' => 'boolean',
    ];
}
