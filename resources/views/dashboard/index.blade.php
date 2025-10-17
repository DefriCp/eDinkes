@extends('layouts.app-dashboard')
@section('title','Dashboard')

@section('content')

{{-- FILTER BULAN/TAHUN --}}
<form method="get" class="mb-4 flex flex-wrap gap-2 items-center">
  <select name="month" class="border rounded px-3 py-2 bg-white">
    @foreach (range(1,12) as $m)
      <option value="{{ $m }}" {{ (int)$m===$month ? 'selected' : '' }}>
        {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
      </option>
    @endforeach
  </select>
  <select name="year" class="border rounded px-3 py-2 bg-white">
    @foreach (range(now()->year-3, now()->year+1) as $y)
      <option value="{{ $y }}" {{ (int)$y===$year ? 'selected' : '' }}>{{ $y }}</option>
    @endforeach
  </select>
  <button class="px-4 py-2 bg-black text-white rounded">Terapkan</button>
</form>

@php
  $delta = $totalVisitsPrev ? (($totalVisitsMonth-$totalVisitsPrev)/$totalVisitsPrev*100) : 0;
@endphp

{{-- CARDS --}}
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-4">
  <div class="rounded-xl p-5 text-white shadow bg-amber-500">
    <div class="text-sm opacity-90">Visits (bulan ini)</div>
    <div class="text-3xl font-extrabold mt-1">{{ number_format($totalVisitsMonth) }}</div>
    <div class="text-sm mt-1">
      <span class="{{ $delta>=0 ? 'text-emerald-900' : 'text-rose-900' }}">
        {{ $delta>=0 ? '↑' : '↓' }} {{ number_format($delta,2) }}%
      </span>
      <span class="opacity-90">vs bulan lalu</span>
    </div>
  </div>

  <div class="rounded-xl p-5 text-white shadow bg-blue-600">
    <div class="text-sm opacity-90">Puskesmas</div>
    <div class="text-3xl font-extrabold mt-1">{{ number_format($puskesmasCount) }}</div>
  </div>

  <div class="rounded-xl p-5 text-white shadow bg-indigo-500">
    <div class="text-sm opacity-90">Puskesmas Pembantu</div>
    <div class="text-3xl font-extrabold mt-1">{{ number_format($puskesmasPembantuCount) }}</div>
  </div>

  <div class="rounded-xl p-5 text-white shadow bg-pink-500">
    <div class="text-sm opacity-90">Posyandu</div>
    <div class="text-3xl font-extrabold mt-1">{{ number_format($posyanduCount) }}</div>
  </div>

  <div class="rounded-xl p-5 text-white shadow bg-cyan-500">
    <div class="text-sm opacity-90">Total 10 Diagnosa (agregat)</div>
    <div class="text-3xl font-extrabold mt-1">{{ number_format($diagnoses->sum('curr')) }}</div>
  </div>
</div>

{{-- CHARTS --}}
<div class="mt-6 grid grid-cols-1 xl:grid-cols-3 gap-4">
  <div class="xl:col-span-2 glass rounded-xl p-5 shadow">
    <div class="flex items-center justify-between mb-2">
      <div>
        <div class="font-semibold text-lg">Kunjungan per Bulan</div>
        <div class="text-sm text-gray-600">Tahun {{ $year }}</div>
      </div>
      <a class="px-3 py-2 rounded bg-indigo-600 text-white text-sm">Download Report</a>
    </div>
    <canvas id="lineChart" height="120"></canvas>
  </div>

  <div class="glass rounded-xl p-5 shadow">
    <div class="font-semibold mb-2">
      Obat Teratas ({{ \Carbon\Carbon::create()->month($month)->translatedFormat('F') }})
      <span class="text-gray-500 text-xs">*muncul jika tabel agregat obat tersedia</span>
    </div>
    <canvas id="barChart" height="200"></canvas>
  </div>
</div>

{{-- TOP TABLES --}}
<div class="mt-6 grid grid-cols-1 lg:grid-cols-2 gap-4">
  {{-- Diagnosa agregat (opsional) --}}
  <div class="glass rounded-xl p-5 shadow">
    <div class="font-semibold mb-3">Top-10 Diagnosa (Agregat Bulanan)</div>
    <div class="overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead>
          <tr class="text-left text-gray-600 border-b">
            <th class="py-2">No</th>
            <th>Kode</th>
            <th>Nama</th>
            <th class="text-right">{{ \Carbon\Carbon::create()->month($prevMonth)->translatedFormat('F') }} {{ $prevYear }}</th>
            <th class="text-right">{{ \Carbon\Carbon::create()->month($month)->translatedFormat('F') }} {{ $year }}</th>
          </tr>
        </thead>
        <tbody class="divide-y">
          @forelse($diagnoses as $i => $d)
            <tr>
              <td class="py-2">{{ $i+1 }}</td>
              <td>{{ $d->code }}</td>
              <td>{{ $d->name }}</td>
              <td class="text-right">{{ number_format($d->prev) }}</td>
              <td class="text-right font-semibold">{{ number_format($d->curr) }}</td>
            </tr>
          @empty
            <tr><td class="py-3 text-center text-gray-500" colspan="5">Tidak ada data agregat.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  {{-- Diagnosa dari visits asli --}}
  <div class="glass rounded-xl p-5 shadow">
    <div class="font-semibold mb-3">10 Diagnosa Terbanyak (Data Kunjungan Asli)</div>
    <div class="overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead>
          <tr class="text-left text-gray-600 border-b">
            <th class="py-2">No</th>
            <th>Diagnosa</th>
            <th class="text-right">Total</th>
          </tr>
        </thead>
        <tbody class="divide-y">
          @forelse($topDiagnosesFromVisits as $i => $d)
            <tr>
              <td class="py-2">{{ $i+1 }}</td>
              <td>{{ $d->diagnosa ?? '-' }}</td>
              <td class="text-right font-semibold">{{ number_format($d->total) }}</td>
            </tr>
          @empty
            <tr><td colspan="3" class="py-3 text-center text-gray-500">Belum ada data.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

{{-- SCRIPTS CHART.JS --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  // Line chart
  const ctx1 = document.getElementById('lineChart').getContext('2d');
  new Chart(ctx1, {
    type: 'line',
    data: {
      labels: @json($lineLabels),
      datasets: [{
        label: 'Kunjungan',
        data: @json($lineData),
        tension: .35,
        fill: true,
        backgroundColor: 'rgba(99,102,241,.15)',
        borderColor: '#6366f1',
        pointRadius: 0,
        borderWidth: 2
      }]
    },
    options: { plugins:{ legend:{ display:false } }, scales:{ x:{ grid:{ display:false } } } }
  });

  // Bar chart (hanya isi jika ada data agregat obat)
  const barLabels = @json($drugs->pluck('name'));
  const barData   = @json($drugs->pluck('curr'));
  if (barLabels.length) {
    const ctx2 = document.getElementById('barChart').getContext('2d');
    new Chart(ctx2, {
      type: 'bar',
      data: { labels: barLabels, datasets: [{ data: barData, backgroundColor: '#0ea5e9' }] },
      options: { plugins:{ legend:{ display:false } }, indexAxis: 'y' }
    });
  }
</script>
@endsection
