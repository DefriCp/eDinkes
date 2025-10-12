<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    
    protected $fillable = [
        'facility_type','facility_id','tanggal',
        'nama_pasien','no_erm','nik','no_rm_lama','no_dokumen_rm',
        'jenis_kelamin','tempat_lahir','tanggal_lahir','umur','pekerjaan',
        'alamat','agama','status_pernikahan',
        'facility_kecamatan_id','facility_kecamatan_nama',
        'facility_desa_id','facility_desa_nama',
        'patient_kecamatan_id','patient_kecamatan_nama',
        'patient_desa_id','patient_desa_nama',
        'nama_ayah','jenis_kunjungan','kunjungan','poli',
        'asuransi','no_asuransi','diagnosa','jenis_kasus',
        ];
    

    public function facilityName(): string
    {
        return $this->facility_type === 'puskesmas'
            ? optional(\App\Models\Puskesmas::find($this->facility_id))->name ?? '-'
            : optional(\App\Models\Posyandu::find($this->facility_id))->name ?? '-';
    }
}
