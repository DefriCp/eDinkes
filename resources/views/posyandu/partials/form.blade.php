@php $r = $row ?? null; @endphp

<div class="grid md:grid-cols-2 gap-4">
  <div>
    <label class="block text-sm mb-1">ID Posyandu</label>
    <input name="id_posyandu" value="{{ old('id_posyandu',$r->id_posyandu??'') }}" class="w-full border rounded h-10 px-3">
  </div>
  <div>
    <label class="block text-sm mb-1">No. Registrasi</label>
    <input name="noregistrasi" value="{{ old('noregistrasi',$r->noregistrasi??'') }}" class="w-full border rounded h-10 px-3">
  </div>

  <div>
    <label class="block text-sm mb-1">Nama Posyandu</label>
    <input name="nama_posyandu" value="{{ old('nama_posyandu',$r->nama_posyandu??'') }}" class="w-full border rounded h-10 px-3">
  </div>
  <div>
    <label class="block text-sm mb-1">Nama Puskesmas</label>
    <input name="nama_puskesmas" value="{{ old('nama_puskesmas',$r->nama_puskesmas??'') }}" class="w-full border rounded h-10 px-3">
  </div>

  <div>
    <label class="block text-sm mb-1">Provinsi</label>
    <input name="provinsi" value="{{ old('provinsi',$r->provinsi??'') }}" class="w-full border rounded h-10 px-3">
  </div>
  <div>
    <label class="block text-sm mb-1">Kabupaten</label>
    <input name="kabupaten" value="{{ old('kabupaten',$r->kabupaten??'') }}" class="w-full border rounded h-10 px-3">
  </div>
  <div>
    <label class="block text-sm mb-1">Kecamatan</label>
    <input name="kecamatan" value="{{ old('kecamatan',$r->kecamatan??'') }}" class="w-full border rounded h-10 px-3">
  </div>
  <div>
    <label class="block text-sm mb-1">Kode Desa</label>
    <input name="kode_desa" value="{{ old('kode_desa',$r->kode_desa??'') }}" class="w-full border rounded h-10 px-3">
  </div>
  <div class="md:col-span-2">
    <label class="block text-sm mb-1">Desa</label>
    <input name="desa" value="{{ old('desa',$r->desa??'') }}" class="w-full border rounded h-10 px-3">
  </div>

  <div>
    <label class="block text-sm mb-1">Kriteria 1</label>
    <input name="kriteria_1" value="{{ old('kriteria_1',$r->kriteria_1??'') }}" class="w-full border rounded h-10 px-3">
  </div>
  <div>
    <label class="block text-sm mb-1">Kriteria 2</label>
    <input name="kriteria_2" value="{{ old('kriteria_2',$r->kriteria_2??'') }}" class="w-full border rounded h-10 px-3">
  </div>
  <div>
    <label class="block text-sm mb-1">Kriteria 3</label>
    <input name="kriteria_3" value="{{ old('kriteria_3',$r->kriteria_3??'') }}" class="w-full border rounded h-10 px-3">
  </div>

  <div>
    <label class="block text-sm mb-1">Status Posyandu Aktif</label>
    <input name="status_posyandu_aktif" value="{{ old('status_posyandu_aktif',$r->status_posyandu_aktif??'') }}" class="w-full border rounded h-10 px-3" placeholder="AKTIF / TIDAK">
  </div>

  <div>
    <label class="block text-sm mb-1">Kriteria Siklus Hidup 1</label>
    <input name="kriteria_siklus_hidup_1" value="{{ old('kriteria_siklus_hidup_1',$r->kriteria_siklus_hidup_1??'') }}" class="w-full border rounded h-10 px-3">
  </div>
  <div>
    <label class="block text-sm mb-1">Kriteria Siklus Hidup 2</label>
    <input name="kriteria_siklus_hidup_2" value="{{ old('kriteria_siklus_hidup_2',$r->kriteria_siklus_hidup_2??'') }}" class="w-full border rounded h-10 px-3">
  </div>
  <div>
    <label class="block text-sm mb-1">Kriteria Siklus Hidup 3</label>
    <input name="kriteria_siklus_hidup_3" value="{{ old('kriteria_siklus_hidup_3',$r->kriteria_siklus_hidup_3??'') }}" class="w-full border rounded h-10 px-3">
  </div>

  <div class="md:col-span-2">
    <label class="block text-sm mb-1">Status Posyandu Siklus Hidup</label>
    <input name="status_posyandu_siklus_hidup" value="{{ old('status_posyandu_siklus_hidup',$r->status_posyandu_siklus_hidup??'') }}" class="w-full border rounded h-10 px-3" placeholder="(contoh: Sudah Siklus Hidup / Belum Siklus Hidup)">
  </div>
</div>
