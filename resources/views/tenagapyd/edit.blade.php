@extends('layouts.app-dashboard')
@section('content')
<div class="max-w-4xl mx-auto p-6">
  <h1 class="text-xl font-semibold mb-4">Edit Tenaga PYD</h1>
  <form action="{{ route('tenagapyd.update',$row) }}" method="post" class="space-y-4">
    @csrf @method('PUT')
    @include('tenagapyd.partials.form', ['row'=>$row])
    <div class="flex gap-2">
      <a href="{{ route('tenagapyd.index') }}" class="px-4 h-10 rounded border">Batal</a>
      <button class="px-4 h-10 rounded bg-blue-600 text-white">Update</button>
    </div>
  </form>
</div>
@endsection
