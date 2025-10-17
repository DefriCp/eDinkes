<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title','Dashboard')</title>

  {{-- Tailwind --}}
  <script src="https://cdn.tailwindcss.com"></script>

  {{-- Alpine (toggle sidebar) --}}
  <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

  {{-- Chart.js --}}
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <style>
    ::-webkit-scrollbar { width:8px; height:8px }
    ::-webkit-scrollbar-thumb { background:#cbd5e1; border-radius:9999px }
    .glass { background:rgba(255,255,255,.8); backdrop-filter:blur(8px) }
  </style>
</head>
<body class="bg-gray-100 text-gray-800" x-data="{ open: true }">
  {{-- TOPBAR --}}
  <header class="bg-black text-white relative z-30">
  </header>
  <div class="flex relative">
    {{-- SIDEBAR --}}
<aside class="bg-white border-r min-h-[calc(100vh-56px)] transition-all duration-200 sticky top-0 z-20"
       x-data="{ open: true }"
       :class="open ? 'w-64' : 'w-16'">
  <div class="p-4 font-bold text-lg">Dinas Kesehatan</div>
  <nav class="px-2 space-y-1">
    {{-- Dashboard --}}
    <a href="{{ route('dashboard') }}"
       class="flex items-center gap-3 px-3 py-2 rounded hover:bg-gray-100
              {{ request()->routeIs('dashboard*') ? 'bg-gray-100 text-gray-900' : 'text-gray-700' }}">
      <x-heroicon-o-squares-2x2 class="w-5 h-5 shrink-0" aria-hidden="true"/>
      <span x-show="open" x-transition x-cloak>Dashboard</span>
      <span x-show="!open" class="sr-only">Dashboard</span>
    </a>

    {{-- Formulir Inputan (dropdown) --}}
    <div
      x-data="{
        openMenu: {{ request()->routeIs('visits.*') || request()->routeIs('units.*') || request()->routeIs('vehicles.*') || request()->routeIs('posyandu.*') || request()->routeIs('tenaga.*') ? 'true':'false' }},
      }"
      x-effect="if(!$root.__x.$data.open) openMenu = false"  {{-- tutup submenu saat sidebar collapse --}}
      class="px-2"
    >
      <button type="button"
              @click="if($root.__x.$data.open) openMenu = !openMenu"
              :aria-expanded="openMenu"
              class="w-full flex items-center justify-between gap-3 px-3 py-2 rounded hover:bg-gray-100
                    text-gray-700">
        <span class="flex items-center gap-3">
          <x-heroicon-o-rectangle-group class="w-5 h-5 shrink-0" aria-hidden="true"/>
          <span x-show="open" x-transition x-cloak>Formulir Inputan</span>
          <span x-show="!open" class="sr-only">Formulir Inputan</span>
        </span>

        {{-- caret hanya saat sidebar terbuka --}}
        <x-heroicon-o-chevron-down class="w-4 h-4 transition"
          x-show="open" x-cloak
          :class="openMenu ? 'rotate-180' : ''" aria-hidden="true"/>
      </button>

      {{-- Submenu --}}
      <div x-show="open && openMenu" x-collapse x-cloak class="mt-1 ml-8 space-y-1">
        {{-- Kunjungan --}}
        <a href="{{ route('visits.index') }}"
          class="flex items-center gap-2 px-3 py-2 rounded hover:bg-gray-100
                  {{ request()->routeIs('visits.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-700' }}">
          <x-heroicon-o-clipboard-document-list class="w-4 h-4 shrink-0" aria-hidden="true"/>
          <span>Kunjungan</span>
        </a>

        {{-- Daftar Unit Puskesmas --}}
        <a href="{{ route('units.index') }}"
          class="flex items-center gap-2 px-3 py-2 rounded hover:bg-gray-100
                  {{ request()->routeIs('units.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-700' }}">
          <x-heroicon-o-building-office-2 class="w-4 h-4 shrink-0" aria-hidden="true"/>
          <span>Daftar Unit Puskesmas</span>
        </a>

        {{-- Daftar Kendaraan Puskesmas --}}
        <a href="{{ route('vehicles.index') }}"
          class="flex items-center gap-2 px-3 py-2 rounded hover:bg-gray-100
                  {{ request()->routeIs('vehicles.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-700' }}">
          <x-heroicon-o-truck class="w-4 h-4 shrink-0" aria-hidden="true"/>
          <span>Daftar Kendaraan Puskesmas</span>
        </a>

        {{-- Daftar Jumlah Posyandu --}}
        <a href="{{ route('posyandu.index') }}"
          class="flex items-center gap-2 px-3 py-2 rounded hover:bg-gray-100
                  {{ request()->routeIs('posyandu.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-700' }}">
          <x-heroicon-o-home-modern class="w-4 h-4 shrink-0" aria-hidden="true"/>
          <span>Daftar Jumlah Posyandu</span>
        </a>

        {{-- Daftar Jumlah Tenaga Kesehatan --}}
        <a href="{{ route('tenaga.index') }}"
          class="flex items-center gap-2 px-3 py-2 rounded hover:bg-gray-100
                  {{ request()->routeIs('tenaga.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-700' }}">
          <x-heroicon-o-user-group class="w-4 h-4 shrink-0" aria-hidden="true"/>
          <span>Daftar Jumlah Tenaga Kesehatan</span>
        </a>
      </div>
    </div>

    </a>

    {{-- GIS --}}
    <a href="{{ route('gis.index') }}"
       class="flex items-center gap-3 px-3 py-2 rounded hover:bg-gray-100
              {{ request()->routeIs('gis.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-700' }}">
      <x-heroicon-o-map class="w-5 h-5 shrink-0" aria-hidden="true"/>
      <span x-show="open" x-transition x-cloak>GIS</span>
      <span x-show="!open" class="sr-only">GIS</span>
    </a>
  </nav>
</aside>
    {{-- CONTENT --}}
    <main class="flex-1 p-4 sm:p-6 relative z-10">
      @yield('content')
    </main>
  </div>
</body>
</html>
