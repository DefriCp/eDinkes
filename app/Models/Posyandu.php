<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Posyandu extends Model
{
    protected $fillable = [
        'id_posyandu','provinsi','kabupaten','kecamatan','kode_desa','desa',
        'noregistrasi','nama_puskesmas','nama_posyandu',
        'kriteria_1','kriteria_2','kriteria_3','status_posyandu_aktif',
        'kriteria_siklus_hidup_1','kriteria_siklus_hidup_2','kriteria_siklus_hidup_3',
        'status_posyandu_siklus_hidup'
    ];
}
