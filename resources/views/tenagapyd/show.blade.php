@extends('layouts.app-dashboard')

@section('content')
<div class="max-w-4xl mx-auto p-6">
  <a href="{{ route('tenagapyd.index') }}" class="text-slate-600 hover:underline">&larr; Kembali</a>

  <h1 class="text-2xl font-bold mt-2 mb-4">
    Detail Kader: {{ $row->nama_kader }}
  </h1>

  {{-- helper kecil --}}
  @php
    $cell = function($label, $value) {
      return '<div class="p-3 rounded border bg-white">
                <div class="text-xs text-slate-500">'.e($label).'</div>
                <div class="font-medium">'.( ($value!==null && $value!=='') ? e($value) : '—' ).'</div>
              </div>';
    };
  @endphp

  <div class="grid md:grid-cols-2 gap-4">
    {!! $cell('Nama Kader', $row->nama_kader) !!}
    {!! $cell('Posyandu', $row->nama_posyandu) !!}
    {!! $cell('Puskesmas', $row->nama_puskesmas) !!}
    {!! $cell('Kecamatan', $row->kecamatan) !!}
    {!! $cell('Kabupaten', $row->kabupaten) !!}
    {!! $cell('Provinsi', $row->provinsi) !!}
    {!! $cell('No. Registrasi', $row->noregistrasi) !!}
    {!! $cell('Usia', $row->usia) !!}
    {!! $cell('Jenis Kelamin', $row->jenis_kelamin) !!}
    {!! $cell('Nomor SK', $row->nomor_sk) !!}
    {!! $cell('Jenjang Pendidikan', $row->jenjang_pendidikan) !!}
    {!! $cell('Kepemilikan JKN', $row->kepemilikan_jkn) !!}
    {!! $cell('Metode Koordinasi', $row->metode_koordinasi) !!}
  </div>

  <h2 class="text-xl font-semibold mt-6 mb-2">Alamat</h2>
  <div class="grid md:grid-cols-2 gap-4">
    {!! $cell('Alamat KTP', $row->alamat_ktp) !!}
    {!! $cell('Alamat Domisili', $row->alamat_domisili) !!}
  </div>

  <h2 class="text-xl font-semibold mt-6 mb-2">Status Pelatihan & Keterampilan</h2>
  <div class="grid md:grid-cols-2 gap-4">
    {!! $cell('Pelatihan Posyandu', $row->status_pelatihan_posyandu) !!}
    {!! $cell('Penilaian Posyandu', $row->status_penilaian_posyandu) !!}
    {!! $cell('Keterampilan Posyandu', $row->status_keterampilan_posyandu) !!}

    {!! $cell('Pelatihan Bayi & Balita', $row->status_pelatihan_bayi_balita) !!}
    {!! $cell('Penilaian Bayi & Balita', $row->status_penilaian_bayi_balita) !!}
    {!! $cell('Keterampilan Bayi & Balita', $row->status_keterampilan_bayi_balita) !!}

    {!! $cell('Pelatihan Ibu Hamil', $row->status_pelatihan_ibu_hamil) !!}
    {!! $cell('Penilaian Ibu Hamil', $row->status_penilaian_ibu_hamil) !!}
    {!! $cell('Keterampilan Ibu Hamil', $row->status_keterampilan_ibu_hamil) !!}

    {!! $cell('Pelatihan Remaja', $row->status_pelatihan_remaja) !!}
    {!! $cell('Penilaian Remaja', $row->status_penilaian_remaja) !!}
    {!! $cell('Keterampilan Remaja', $row->status_keterampilan_remaja) !!}

    {!! $cell('Pelatihan Lansia', $row->status_pelatihan_lansia) !!}
    {!! $cell('Penilaian Lansia', $row->status_penilaian_lansia) !!}
    {!! $cell('Keterampilan Lansia', $row->status_keterampilan_lansia) !!}

    {!! $cell('Pelatihan Timbang & Ukur', $row->status_pelatihan_timbang_ukur) !!}
    {!! $cell('Penilaian Timbang & Ukur', $row->status_penilaian_timbang_ukur) !!}

    {!! $cell('Tingkatan Kader', $row->tingkatan_kader) !!}
    {!! $cell('Sudah Mengikuti 25 KD', $row->sudah_mengikuti_25_keterampilan_dasar ? 'Ya' : 'Belum') !!}
    {!! $cell('Sudah Dinilai 25 KD', $row->sudah_dinilai_keterampilan_dasar ? 'Ya' : 'Belum') !!}
  </div>

  <div class="mt-6 flex gap-2">
    <a href="{{ route('tenagapyd.edit',$row) }}" class="px-4 h-10 rounded border">Edit</a>
    <form action="{{ route('tenagapyd.destroy',$row) }}" method="post"
          onsubmit="return confirm('Hapus data ini?')">
      @csrf @method('DELETE')
      <button class="px-4 h-10 rounded border border-red-400 text-red-600">Hapus</button>
    </form>
  </div>
</div>
@endsection
