@extends('layouts.app-dashboard')
@section('content')
<h2 class="text-xl font-semibold mb-4">Tambah Kunjungan Pasien</h2>
<form method="POST" action="{{ route('visits.store') }}">
  @include('visits.form')
</form>
@endsection
