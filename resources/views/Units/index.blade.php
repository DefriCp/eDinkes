@extends('layouts.app-dashboard')
@section('title','Daftar Unit Puskesmas')

@section('content')
  <div class="flex items-center justify-between mb-4">
    <h1 class="text-2xl font-semibold">Daftar Unit Puskesmas</h1>
    <div class="flex gap-2">
      <a href="#" class="px-3 py-2 rounded bg-black text-white text-sm">Tambah Unit</a>
      <a href="#" class="px-3 py-2 rounded border text-sm">Export</a>
    </div>
  </div>
  <div class="bg-white rounded-xl shadow overflow-x-auto">
    <table class="w-full text-sm border border-gray-300">
      {{-- HEADER: 2 baris, netral --}}
      <thead>
        <tr class="bg-gray-50">
          <th rowspan="2" class="px-3 py-2 text-center border border-gray-300 w-12">No</th>
          <th rowspan="2" class="px-3 py-2 text-left   border border-gray-300 w-56">Puskesmas</th>
          <th rowspan="2" class="px-3 py-2 text-left   border border-gray-300">Alamat</th>
          <th colspan="2" class="px-3 py-2 text-center border border-gray-300 w-48">Kemampuan Layanan</th>
          <th rowspan="2" class="px-3 py-2 text-center border border-gray-300 w-24">Jumlah Desa</th>
        </tr>
        <tr class="bg-gray-50">
          <th class="px-3 py-2 text-center border border-gray-300">DTP/Non</th>
          <th class="px-3 py-2 text-center border border-gray-300">PONED/Non</th>
        </tr>
      </thead>
    </table>
  </div>
@endsection
