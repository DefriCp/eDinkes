@extends('layouts.app')

@php
  function nf($n){ return number_format((int)$n, 0, ',', '.'); }
  $monthNames = [1=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
@endphp

@section('content')
  <div class="mb-6 flex items-center gap-3">
    <form method="get" class="flex items-center gap-2">
      <select name="month" class="border rounded px-3 py-2 bg-white">
        @for($m=1; $m<=12; $m++)
          <option value="{{ $m }}" {{ $m==(int)($month??now()->month) ? 'selected':'' }}>
            {{ $monthNames[$m] }}
          </option>
        @endfor
      </select>
      <input type="number" name="year" value="{{ $year ?? now()->year }}" class="border rounded px-3 py-2 w-28">
      <button class="px-4 py-2 rounded bg-blue-700 text-white">Tampilkan</button>
    </form>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="card">
      <div class="bg-indigo-800 text-white px-4 py-3 rounded-t-lg font-semibold">
        KASUS DIAGNOSA TERBANYAK BULAN {{ strtoupper($monthNames[$month]) }} {{ $year }}
      </div>
      <div class="p-4 overflow-auto">
        <table class="w-full">
          <thead class="bg-gray-100">
            <tr>
              <th class="w-12 text-center">No</th>
              <th class="w-20">Kode</th>
              <th class="text-left">Nama</th>
              <th class="w-40 text-right">{{ $monthNames[$prevMonth] }} {{ $prevYear }}</th>
              <th class="w-40 text-right">{{ $monthNames[$month] }} {{ $year }}</th>
            </tr>
          </thead>
          <tbody>
            @foreach($diagnoses as $i => $d)
              <tr class="border-b">
                <td class="text-center font-semibold">{{ $i+1 }}</td>
                <td class="font-semibold">{{ $d->code }}</td>
                <td>{{ $d->name }}</td>
                <td class="text-right">{{ nf($d->prev) }}</td>
                <td class="text-right font-semibold">{{ nf($d->curr) }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

    <div class="card">
      <div class="bg-indigo-800 text-white px-4 py-3 rounded-t-lg font-semibold">
        PENGGUNAAN OBAT TERBANYAK BULAN {{ strtoupper($monthNames[$month]) }} {{ $year }}
      </div>
      <div class="p-4 overflow-auto">
        <table class="w-full">
          <thead class="bg-gray-100">
            <tr>
              <th class="w-12 text-center">No</th>
              <th class="text-left">Nama</th>
              <th class="w-40 text-right">{{ $monthNames[$prevMonth] }} {{ $prevYear }}</th>
              <th class="w-40 text-right">{{ $monthNames[$month] }} {{ $year }}</th>
            </tr>
          </thead>
          <tbody>
            @foreach($drugs as $i => $row)
              @php $up = $row->curr > $row->prev; @endphp
              <tr class="border-b">
                <td class="text-center font-semibold">{{ $i+1 }}</td>
                <td>{{ $row->name }}</td>
                <td class="text-right">{{ nf($row->prev) }}</td>
                <td class="text-right font-semibold {{ $up ? 'text-green-600' : '' }}">{{ nf($row->curr) }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
@endsection
