@extends('layouts.app-dashboard')
@section('title','Daftar Kendaraan Puskesmas')

@section('content')
  <div class="flex items-center justify-between mb-4">
    <h1 class="text-2xl font-semibold">Daftar Kendaraan Puskesmas</h1>
    <div class="flex gap-2">
      <a href="#" class="px-3 py-2 rounded bg-black text-white text-sm">Tambah Kendaraan</a>
      <a href="#" class="px-3 py-2 rounded border text-sm">Export</a>
    </div>
  </div>

  <div class="bg-white rounded-xl shadow overflow-x-auto">
    <table class="min-w-full text-sm">
      <thead class="bg-gray-50">
        <tr class="text-left text-gray-600">
          <th class="py-3 px-4">#</th>
          <th class="px-4">No. Polisi</th>
          <th class="px-4">Jenis</th>
          <th class="px-4">Unit / Puskesmas</th>
          <th class="px-4">Kondisi</th>
          <th class="px-4 text-right">Aksi</th>
        </tr>
      </thead>
      <tbody class="divide-y">
        <tr>
          <td class="py-3 px-4" colspan="6">
            <div class="text-center text-gray-500">Belum ada data. Silakan klik <b>Tambah Kendaraan</b>.</div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
@endsection
