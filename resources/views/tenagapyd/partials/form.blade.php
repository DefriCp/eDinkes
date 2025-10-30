@php $r = $row ?? null; @endphp
<div class="grid md:grid-cols-2 gap-4">
  <div>
    <label class="block text-sm mb-1">Nama Kader</label>
    <input name="nama_kader" value="{{ old('nama_kader',$r->nama_kader??'') }}" class="w-full border rounded h-10 px-3">
    @error('nama_kader') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
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
    <label class="block text-sm mb-1">Kecamatan</label>
    <input name="kecamatan" value="{{ old('kecamatan',$r->kecamatan??'') }}" class="w-full border rounded h-10 px-3">
  </div>
  <div>
    <label class="block text-sm mb-1">Kabupaten</label>
    <input name="kabupaten" value="{{ old('kabupaten',$r->kabupaten??'') }}" class="w-full border rounded h-10 px-3">
  </div>
  <div>
    <label class="block text-sm mb-1">Provinsi</label>
    <input name="provinsi" value="{{ old('provinsi',$r->provinsi??'') }}" class="w-full border rounded h-10 px-3">
  </div>
  <div>
    <label class="block text-sm mb-1">Usia</label>
    <input type="number" min="0" max="120" name="usia" value="{{ old('usia',$r->usia??'') }}" class="w-full border rounded h-10 px-3">
  </div>
  <div>
    <label class="block text-sm mb-1">Jenis Kelamin</label>
    <select name="jenis_kelamin" class="w-full border rounded h-10 px-3">
      <option value="">- pilih -</option>
      <option value="L" @selected(old('jenis_kelamin',$r->jenis_kelamin??'')==='L')>L</option>
      <option value="P" @selected(old('jenis_kelamin',$r->jenis_kelamin??'')==='P')>P</option>
    </select>
  </div>

  <div class="md:col-span-2">
    <label class="block text-sm mb-1">Alamat Domisili</label>
    <textarea name="alamat_domisili" rows="2" class="w-full border rounded px-3">{{ old('alamat_domisili',$r->alamat_domisili??'') }}</textarea>
  </div>

  <div>
    <label class="block text-sm mb-1">Tingkatan Kader</label>
    <input name="tingkatan_kader" value="{{ old('tingkatan_kader',$r->tingkatan_kader??'') }}" class="w-full border rounded h-10 px-3">
  </div>

  <div>
    <label class="inline-flex items-center gap-2">
      <input type="checkbox" name="sudah_mengikuti_25_keterampilan_dasar" value="1"
             @checked(old('sudah_mengikuti_25_keterampilan_dasar',$r->sudah_mengikuti_25_keterampilan_dasar??false))>
      <span class="text-sm">Sudah mengikuti 25 keterampilan dasar</span>
    </label>
  </div>
  <div>
    <label class="inline-flex items-center gap-2">
      <input type="checkbox" name="sudah_dinilai_keterampilan_dasar" value="1"
             @checked(old('sudah_dinilai_keterampilan_dasar',$r->sudah_dinilai_keterampilan_dasar??false))>
      <span class="text-sm">Sudah dinilai keterampilan dasar</span>
    </label>
  </div>
</div>
