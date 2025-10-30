@extends('layouts.app-dashboard')

@section('content')
<div class="max-w-3xl mx-auto">
  <h1 class="text-2xl font-semibold mb-4">Import Kunjungan</h1>

  @if ($errors->any())
    <div class="mb-4 p-3 rounded bg-red-50 text-red-700">
      <ul class="list-disc list-inside">
        @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
      </ul>
    </div>
  @endif

  @if (session('success'))
    <div class="mb-4 p-3 rounded bg-emerald-50 text-emerald-700">
      {{ session('success') }}
    </div>
  @endif

  @if (session('import_errors'))
    <div class="mb-4 p-3 rounded bg-yellow-50 text-yellow-800">
      <p class="font-semibold mb-1">Baris dilewati:</p>
      <ul class="list-disc list-inside text-sm space-y-1 max-h-48 overflow-auto">
        @foreach (session('import_errors') as $e) <li>{{ $e }}</li> @endforeach
      </ul>
    </div>
  @endif

  <form method="post" action="{{ route('visits.import.store') }}" enctype="multipart/form-data" class="space-y-4 bg-white p-4 rounded shadow">
    @csrf

    <div>
      <label class="block text-sm font-medium mb-1">File (.xlsx / .csv)</label>
      <input type="file" name="file" accept=".xlsx,.csv" required class="w-full border rounded px-3 py-2">
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
        <label class="block text-sm font-medium mb-1">Jenis Fasilitas</label>
        <select name="facility_type" class="w-full border rounded px-3 py-2" required>
          <option value="puskesmas">Puskesmas</option>
          <option value="puskesmas_pembantu">Puskesmas Pembantu</option>
          <option value="posyandu">Posyandu</option>
        </select>
      </div>
      <div>
        <label class="block text-sm font-medium mb-1">Facility ID (opsional)</label>
        <input type="number" name="facility_id" class="w-full border rounded px-3 py-2" placeholder="mis. 12">
      </div>
    </div>

    <div class="flex items-center gap-2">
      <button class="px-4 py-2 rounded bg-emerald-600 text-white hover:bg-emerald-700">Import</button>
      <a href="{{ route('visits.index') }}" class="px-3 py-2 rounded border">Kembali</a>
    </div>

    <p class="text-sm text-slate-600 mt-3">
      Header dikenali: No., Tanggal, Nama Pasien, No eRM, NIK, No. RM Lama, No. Dokumen RM, Jenis Kelamin, Tempat &amp; Tgl.Lahir, Umur, Pekerjaan, Alamat, Agama, Status Pernikahan, Kelurahan, Nama Ayah, Jenis Kunjungan, Kunjungan, Poli/Ruangan, Asuransi, No. Asuransi, Kode Diagnosa, Diagnosa, Jenis Kasus
    </p>
  </form>
</div>
@endsection
