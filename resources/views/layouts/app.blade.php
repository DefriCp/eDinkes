<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ config('app.name','Dinkes Dashboard') }}</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    .card { @apply bg-white rounded-lg shadow border border-gray-200; }
    th, td { @apply px-4 py-2; }
    thead th { @apply text-xs font-semibold uppercase tracking-wide text-gray-600; }
    tbody td { @apply text-sm text-gray-800; }
    tbody tr:nth-child(even) { @apply bg-gray-50; }
    a.nav { @apply text-blue-700 underline; }
  </style>
</head>
<body class="bg-gray-100">
  <div class="max-w-7xl mx-auto p-6">
    @auth
      <div class="mb-4 flex gap-3 items-center">
        <a class="nav" href="{{ route('dashboard') }}">Dashboard</a>
        <a class="nav" href="{{ route('gis.index') }}">GIS</a>
        <a class="nav" href="{{ route('visits.create') }}">Input Pasien</a>
        <a class="nav" href="{{ route('visits.index') }}">Daftar Pasien</a>
        <a class="nav" href="{{ route('visits.index') }}">Input Obat - Obatan Keluar</a>
        <form method="POST" action="{{ route('logout') }}" class="ml-auto">@csrf
          <button class="px-3 py-2 rounded bg-gray-800 text-white">Logout</button>
        </form>
      </div>
    @endauth
    @yield('content')
  </div>
</body>
</html>
