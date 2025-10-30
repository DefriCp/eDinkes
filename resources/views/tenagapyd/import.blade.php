@extends('layouts.app-dashboard')
@section('content')
<div class="max-w-xl mx-auto p-6">
  <h1 class="text-xl font-semibold mb-4">Import Excel/CSV Tenaga PYD</h1>

  <div class="mb-4 p-3 bg-slate-50 border rounded text-xs">
    Header yang didukung (harus sama persis):<br>
    <code>provinsi, kabupaten, kecamatan, noregistrasi, nama_puskesmas, nama_posyandu, nama_kader, usia, jenis_kelamin, nomor_sk, jenjang_pendidikan, alamat_ktp, alamat_domisili, kepemilikan_jkn, metode_koordinasi, status_pelatihan_posyandu, status_penilaian_posyandu, status_keterampilan_posyandu, status_pelatihan_bayi_balita, status_penilaian_bayi_balita, status_keterampilan_bayi_balita, status_pelatihan_ibu_hamil, status_penilaian_ibu_hamil, status_keterampilan_ibu_hamil, status_pelatihan_remaja, status_penilaian_remaja, status_keterampilan_remaja, status_pelatihan_lansia, status_penilaian_lansia, status_keterampilan_lansia, status_pelatihan_timbang_ukur, status_penilaian_timbang_ukur, tingkatan_kader, sudah_mengikuti_25_keterampilan_dasar, sudah_dinilai_keterampilan_dasar</code>
  </div>

  <form action="{{ route('tenagapyd.import.store') }}" method="post" enctype="multipart/form-data" class="space-y-4">
    @csrf
    <input type="file" name="file" accept=".xlsx,.xls,.csv" class="w-full border rounded h-10 px-3" required>
    @error('file') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
    <div class="flex gap-2">
      <a href="{{ route('tenagapyd.index') }}" class="px-4 h-10 rounded border">Batal</a>
      <button class="px-4 h-10 rounded bg-blue-600 text-white">Upload & Import</button>
    </div>
  </form>
</div>
@endsection
