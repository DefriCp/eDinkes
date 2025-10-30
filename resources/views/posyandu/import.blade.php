@extends('layouts.app-dashboard')

@section('content')
<div class="max-w-xl mx-auto p-6">
  <h1 class="text-xl font-semibold mb-4">Import Posyandu (Excel / CSV)</h1>

  <div class="mb-4 text-sm text-gray-600">
    <div class="font-semibold mb-1">Format header yang diterima:</div>
    <code class="block bg-gray-50 border rounded p-3 text-xs overflow-x-auto">
      id_posyandu, provinsi, kabupaten, kecamatan, kode_desa, desa,
      noregistrasi, nama_puskesmas, nama_posyandu,
      kriteria_1, kriteria_2, kriteria_3, status_posyandu_aktif,
      kriteria_siklus_hidup_1, kriteria_siklus_hidup_2, kriteria_siklus_hidup_3,
      status_posyandu_siklus_hidup
    </code>
    <div class="mt-2">File: .xlsx / .xls / .csv (maks 20MB). Header tidak case-sensitive.</div>
  </div>

  @if ($errors->any())
    <div class="mb-4 p-3 border rounded bg-rose-50 text-rose-700">
      <ul class="list-disc ml-5 text-sm">
        @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
      </ul>
    </div>
  @endif

  <form action="{{ route('posyandu.import.store') }}" method="post" enctype="multipart/form-data" class="space-y-4">
    @csrf
    <input type="file" name="file" accept=".xlsx,.xls,.csv" required class="block w-full border rounded p-2 bg-white">
    <div class="flex gap-2">
      <a href="{{ route('posyandu.index') }}" class="px-4 h-10 rounded border flex items-center">Batal</a>
      <button class="px-4 h-10 rounded bg-blue-600 text-white">Upload &amp; Import</button>
    </div>
  </form>
</div>
@endsection
