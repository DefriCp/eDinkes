@extends('layouts.app-dashboard')

@section('title','Kunjungan')

@section('content')
<div class="mb-4 flex items-center gap-2">
  <a href="{{ route('visits.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded">+ Tambah Kunjungan</a>
</div>

@if(session('success'))
  <div class="p-3 bg-green-100 text-green-800 rounded mb-3">{{ session('success') }}</div>
@endif

<div class="card p-4">
  <table class="w-full">
    <thead class="bg-gray-100">
      <tr>
        <th class="p-2 text-left">Tanggal</th>
        <th class="p-2">Faskes</th>
        <th class="p-2 text-left">Nama Pasien</th>
        <th class="p-2">JK</th>
        <th class="p-2 text-left">Diagnosa</th>
        <th class="p-2 w-40">Aksi</th>
      </tr>
    </thead>
    <tbody>
      @forelse($visits as $v)
      <tr class="border-b">
        <td class="p-2">{{ \Carbon\Carbon::parse($v->tanggal)->format('d/m/Y') }}</td>
        <td class="p-2 text-center text-xs">
          <span class="uppercase">{{ $v->facility_type_label }}</span><br>
          <span class="text-gray-500 text-[11px]">{{ $v->facility_name }}</span>
        </td>
        <td class="p-2">{{ $v->nama_pasien }}</td>
        <td class="p-2 text-center">{{ $v->jenis_kelamin }}</td>
        <td class="p-2">{{ $v->diagnosa }}</td>
        <td class="p-2">
          <a href="{{ route('visits.edit', $v) }}" class="px-3 py-1 bg-yellow-500 text-white rounded">Edit</a>
          <form action="{{ route('visits.destroy', $v) }}" method="POST" class="inline" onsubmit="return confirm('Hapus kunjungan ini?')">
            @csrf @method('DELETE')
            <button class="px-3 py-1 bg-red-600 text-white rounded">Hapus</button>
          </form>
        </td>
      </tr>
      @empty
      <tr><td class="p-3 text-center text-gray-500" colspan="6">Belum ada data.</td></tr>
      @endforelse
    </tbody>
  </table>

  <div class="mt-4">
    {{ $visits->links() }}
  </div>
</div>
@endsection
