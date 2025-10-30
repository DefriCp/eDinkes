@extends('layouts.app-dashboard')
@section('content')
<div class="max-w-4xl mx-auto p-6">
  <h1 class="text-xl font-semibold mb-4">Tambah Tenaga PYD</h1>
  <form action="{{ route('tenagapyd.store') }}" method="post" class="space-y-4">
    @csrf
    @include('tenagapyd.partials.form')
    <div class="flex gap-2">
      <a href="{{ route('tenagapyd.index') }}" class="px-4 h-10 rounded border">Batal</a>
      <button class="px-4 h-10 rounded bg-blue-600 text-white">Simpan</button>
    </div>
  </form>
</div>
@endsection
