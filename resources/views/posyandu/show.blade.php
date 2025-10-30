@extends('layouts.app-dashboard')

@section('content')
<div class="max-w-4xl mx-auto p-6">
  <a href="{{ route('posyandu.index') }}" class="text-slate-600 hover:underline">&larr; Kembali</a>

  <h1 class="text-2xl font-bold mt-2 mb-4">
    Detail Posyandu: {{ $row->nama_posyandu }}
  </h1>

  @php
    $cell = function($label, $value) {
      return '<div class="p-3 rounded border bg-white">
                <div class="text-xs text-slate-500">'.e($label).'</div>
                <div class="font-medium">'.( ($value!==null && $value!=='') ? e($value) : '—' ).'</div>
              </div>';
    };
  @endphp

  <div class="grid md:grid-cols-2 gap-4">
    {!! $cell('ID Posyandu', $row->id_posyandu) !!}
    {!! $cell('No. Registrasi', $row->noregistrasi) !!}
    {!! $cell('Puskesmas', $row->nama_puskesmas) !!}
    {!! $cell('Provinsi', $row->provinsi) !!}
    {!! $cell('Kabupaten', $row->kabupaten) !!}
    {!! $cell('Kecamatan', $row->kecamatan) !!}
    {!! $cell('Kode Desa', $row->kode_desa) !!}
    {!! $cell('Desa', $row->desa) !!}
    {!! $cell('Status Posyandu Aktif', $row->status_posyandu_aktif) !!}
    {!! $cell('Status Siklus Hidup', $row->status_posyandu_siklus_hidup) !!}
  </div>

  <h2 class="text-xl font-semibold mt-6 mb-2">Kriteria</h2>
  <div class="grid md:grid-cols-2 gap-4">
    {!! $cell('Kriteria 1', $row->kriteria_1) !!}
    {!! $cell('Kriteria 2', $row->kriteria_2) !!}
    {!! $cell('Kriteria 3', $row->kriteria_3) !!}
    {!! $cell('Kriteria Siklus Hidup 1', $row->kriteria_siklus_hidup_1) !!}
    {!! $cell('Kriteria Siklus Hidup 2', $row->kriteria_siklus_hidup_2) !!}
    {!! $cell('Kriteria Siklus Hidup 3', $row->kriteria_siklus_hidup_3) !!}
  </div>

  <div class="mt-6 flex gap-2">
    <a href="{{ route('posyandu.edit',$row) }}" class="px-4 h-10 rounded border">Edit</a>
    <form action="{{ route('posyandu.destroy',$row) }}" method="post" onsubmit="return confirm('Hapus data ini?')">
      @csrf @method('DELETE')
      <button class="px-4 h-10 rounded border border-red-400 text-red-600">Hapus</button>
    </form>
  </div>
</div>
@endsection
