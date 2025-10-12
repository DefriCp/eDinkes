@extends('layouts.app')
@section('content')
<h2 class="text-xl font-semibold mb-4">Edit Kunjungan Pasien</h2>
<form method="POST" action="{{ route('visits.update', $visit) }}">
  @method('PUT')
  @include('visits.form', ['visit'=>$visit])
</form>
@endsection
