@extends('layouts.app-dashboard')
@section('title','Daftar Jumlah Kader Posyandu')

@section('content')
<div class="max-w-6xl mx-auto p-6">
  @if(session('ok')) <div class="mb-4 p-3 bg-emerald-50 border rounded">{{ session('ok') }}</div> @endif

  <div class="flex items-center justify-between gap-3 mb-4">
    <form method="get" class="flex gap-2">
      <input name="q" value="{{ $q }}" class="border rounded px-3 h-10 w-72" placeholder="Cari nama kader / posyandu…">
      <button class="h-10 px-4 rounded bg-slate-800 text-white">Cari</button>
    </form>
    <div class="flex gap-2">
      <a href="{{ route('tenagapyd.import.form') }}" class="h-10 px-4 rounded border">Import</a>
      <a href="{{ route('tenagapyd.create') }}" class="h-10 px-4 rounded bg-blue-600 text-white">Tambah</a>
    </div>
  </div>

  <div class="overflow-x-auto bg-white rounded shadow">
    <table class="min-w-full text-sm">
      <thead class="bg-slate-100">
        <tr>
          <th class="text-left p-3">Nama Kader</th>
          <th class="text-left p-3">Posyandu</th>
          <th class="text-left p-3">JK</th>
          <th class="text-left p-3">Status Pelatihan Kader</th>
          <th class="text-left p-3">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($rows as $r)
          <tr class="border-t">
            <td class="p-3">
              <a href="{{ route('tenagapyd.show', $r->id) }}" class="underline font-semibold">
                {{ $r->nama_kader }}
              </a>
            </td>
            <td class="p-3">{{ $r->nama_posyandu }}</td>
            <td class="p-3">{{ $r->jenis_kelamin }}</td>
            <td class="p-3">
              <span class="inline-block px-2 py-0.5 rounded border">
                {{ $r->status_pelatihan_posyandu ?: '—' }}
              </span>
            </td>
            <td class="p-3">
              <a href="{{ route('tenagapyd.edit',$r->id) }}" class="px-3 py-1 rounded border">Edit</a>
              <form action="{{ route('tenagapyd.destroy',$r->id) }}" method="post" class="inline"
                    onsubmit="return confirm('Hapus data ini?')">
                @csrf @method('DELETE')
                <button class="px-3 py-1 rounded border border-red-400 text-red-600">Hapus</button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="5" class="p-3 text-center text-slate-500">Belum ada data</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="mt-4">{{ $rows->links() }}</div>
</div>
@endsection
