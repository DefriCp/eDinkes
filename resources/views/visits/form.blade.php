@csrf
@php $isEdit = isset($visit); @endphp

{{-- ========== FASILITAS & TANGGAL ========== --}}
<div class="card p-4 mb-4">
  <h3 class="font-semibold mb-3">Fasilitas & Tanggal</h3>

  <div class="grid md:grid-cols-3 gap-3">
    <div>
      <label class="block text-sm">Jenis Fasilitas</label>
      @php $ft = old('facility_type', $visit->facility_type ?? '') @endphp
      <select name="facility_type" id="facility_type" class="border rounded w-full p-2" required>
        <option value="">-- Pilih --</option>
        <option value="puskesmas" {{ $ft=='puskesmas'?'selected':'' }}>Puskesmas</option>
        <option value="puskesmas_pembantu" {{ $ft=='puskesmas_pembantu'?'selected':'' }}>Puskesmas Pembantu</option>
        <option value="posyandu" {{ $ft=='posyandu'?'selected':'' }}>Posyandu</option>
      </select>
    </div>
    <div>
      <label class="block text-sm">Tanggal Kunjungan</label>
      <input type="date" name="tanggal" class="border rounded w-full p-2"
             value="{{ old('tanggal', $visit->tanggal ?? now()->toDateString()) }}" required>
    </div>
    <div>
      <label class="block text-sm">Poli/Ruangan</label>
      <input type="text" name="poli" class="border rounded w-full p-2"
             value="{{ old('poli', $visit->poli ?? '') }}">
    </div>
  </div>

  <div class="grid md:grid-cols-2 gap-3 mt-3">
    <div>
      <label class="block text-sm">Kecamatan (Fasilitas)</label>
      <select id="facility_kecamatan_id" name="facility_kecamatan_id"
              class="border rounded w-full p-2"
              data-selected="{{ old('facility_kecamatan_id', $visit->facility_kecamatan_id ?? '') }}"></select>
      <input type="hidden" id="facility_kecamatan_nama" name="facility_kecamatan_nama"
             value="{{ old('facility_kecamatan_nama', $visit->facility_kecamatan_nama ?? '') }}">
    </div>
    <div>
      <label class="block text-sm">Desa/Kelurahan (Fasilitas)</label>
      <select id="facility_desa_id" name="facility_desa_id"
              class="border rounded w-full p-2"
              data-selected="{{ old('facility_desa_id', $visit->facility_desa_id ?? '') }}"></select>
      <input type="hidden" id="facility_desa_nama" name="facility_desa_nama"
             value="{{ old('facility_desa_nama', $visit->facility_desa_nama ?? '') }}">
      <p class="text-xs text-gray-500 mt-1">* Wajib jika fasilitas = Posyandu</p>
    </div>
  </div>
</div>

{{-- ========== IDENTITAS PASIEN ========== --}}
<div class="card p-4 mb-4">
  <h3 class="font-semibold mb-3">Identitas Pasien</h3>

  <div class="grid md:grid-cols-3 gap-3">
    <div>
      <label class="block text-sm">Nama Pasien</label>
      <input type="text" name="nama_pasien" class="border rounded w-full p-2" required
             value="{{ old('nama_pasien', $visit->nama_pasien ?? '') }}">
    </div>
    <div>
      <label class="block text-sm">No eRM</label>
      <input type="text" name="no_erm" class="border rounded w-full p-2"
             value="{{ old('no_erm', $visit->no_erm ?? '') }}">
    </div>
    <div>
      <label class="block text-sm">NIK</label>
      <input type="text" name="nik" class="border rounded w-full p-2"
             value="{{ old('nik', $visit->nik ?? '') }}">
    </div>

    <div>
      <label class="block text-sm">No. RM Lama</label>
      <input type="text" name="no_rm_lama" class="border rounded w-full p-2"
             value="{{ old('no_rm_lama', $visit->no_rm_lama ?? '') }}">
    </div>
    <div>
      <label class="block text-sm">No. Dokumen RM</label>
      <input type="text" name="no_dokumen_rm" class="border rounded w-full p-2"
             value="{{ old('no_dokumen_rm', $visit->no_dokumen_rm ?? '') }}">
    </div>
    <div>
      <label class="block text-sm">Jenis Kelamin</label>
      @php $jk = old('jenis_kelamin', $visit->jenis_kelamin ?? '') @endphp
      <select name="jenis_kelamin" class="border rounded w-full p-2">
        <option value="">-</option>
        <option value="L" {{ $jk=='L'?'selected':'' }}>Laki-laki</option>
        <option value="P" {{ $jk=='P'?'selected':'' }}>Perempuan</option>
      </select>
    </div>

    <div>
      <label class="block text-sm">Tempat Lahir</label>
      <input type="text" name="tempat_lahir" class="border rounded w-full p-2"
             value="{{ old('tempat_lahir', $visit->tempat_lahir ?? '') }}">
    </div>
    <div>
      <label class="block text-sm">Tanggal Lahir</label>
      <input type="date" name="tanggal_lahir" class="border rounded w-full p-2"
             value="{{ old('tanggal_lahir', $visit->tanggal_lahir ?? '') }}">
    </div>
    <div>
      <label class="block text-sm">Umur (tahun)</label>
      <input type="number" name="umur" min="0" max="150" class="border rounded w-full p-2"
             value="{{ old('umur', $visit->umur ?? '') }}">
    </div>

    <div>
      <label class="block text-sm">Pekerjaan</label>
      <input type="text" name="pekerjaan" class="border rounded w-full p-2"
             value="{{ old('pekerjaan', $visit->pekerjaan ?? '') }}">
    </div>
  </div>
</div>

{{-- ========== ALAMAT PASIEN ========== --}}
<div class="card p-4 mb-4">
  <h3 class="font-semibold mb-3">Alamat Pasien</h3>

<div class="grid grid-cols-1 md:grid-cols-2 gap-3">
  <div>
    <label class="block text-sm">Kecamatan (Pasien)</label>
    <select id="patient_kecamatan_id" name="patient_kecamatan_id"
            class="border rounded w-full p-2">
      <option value="">-- Pilih Kecamatan --</option>
      {{-- opsi diisi via JS --}}
    </select>
    <input type="hidden" id="patient_kecamatan_nama" name="patient_kecamatan_nama"
           value="{{ old('patient_kecamatan_nama', $visit->patient_kecamatan_nama ?? '') }}">
  </div>

  <div>
    <label class="block text-sm">Desa/Kelurahan (Pasien)</label>
    <select id="patient_desa_id" name="patient_desa_id"
            class="border rounded w-full p-2">
      <option value="">-- Pilih Desa --</option>
      {{-- opsi diisi via JS --}}
    </select>
    <input type="hidden" id="patient_desa_nama" name="patient_desa_nama"
           value="{{ old('patient_desa_nama', $visit->patient_desa_nama ?? '') }}">
  </div>
</div>


  <div class="grid md:grid-cols-3 gap-3 mt-3">
    <div>
      <label class="block text-sm">Alamat</label>
      <input type="text" name="alamat" class="border rounded w-full p-2"
             value="{{ old('alamat', $visit->alamat ?? '') }}">
    </div>
    <div>
      <label class="block text-sm">Agama</label>
      <input type="text" name="agama" class="border rounded w-full p-2"
             value="{{ old('agama', $visit->agama ?? '') }}">
    </div>
    <div>
      <label class="block text-sm">Status Pernikahan</label>
      <input type="text" name="status_pernikahan" class="border rounded w-full p-2"
             value="{{ old('status_pernikahan', $visit->status_pernikahan ?? '') }}">
    </div>
  </div>

  <div class="grid md:grid-cols-3 gap-3 mt-3">
    <div>
      <label class="block text-sm">Nama Ayah</label>
      <input type="text" name="nama_ayah" class="border rounded w-full p-2"
             value="{{ old('nama_ayah', $visit->nama_ayah ?? '') }}">
    </div>
  </div>
</div>

{{-- ========== PELAYANAN ========== --}}
<div class="card p-4 mb-4">
  <h3 class="font-semibold mb-3">Pelayanan</h3>

  <div class="grid md:grid-cols-3 gap-3">
    <div>
      <label class="block text-sm">Jenis Kunjungan</label>
      <input type="text" name="jenis_kunjungan" class="border rounded w-full p-2"
             value="{{ old('jenis_kunjungan', $visit->jenis_kunjungan ?? '') }}">
    </div>
    <div>
      <label class="block text-sm">Kunjungan</label>
      <input type="text" name="kunjungan" class="border rounded w-full p-2"
             value="{{ old('kunjungan', $visit->kunjungan ?? '') }}">
    </div>
    <div>
      <label class="block text-sm">Asuransi</label>
      @php $as = old('asuransi', $visit->asuransi ?? '') @endphp
      <select name="asuransi" class="border rounded w-full p-2">
        <option value="">-- Pilih jenis asuransi --</option>
        <option value="UMUM" {{ $as=='UMUM'?'selected':'' }}>UMUM</option>
        <option value="BPJS Kesehatan" {{ $as=='BPJS Kesehatan'?'selected':'' }}>BPJS Kesehatan</option>
        <option value="Pemerintah Daerah Kabupaten Tasikmalaya" {{ $as=='Pemerintah Daerah Kabupaten Tasikmalaya'?'selected':'' }}>Pemerintah Daerah Kabupaten Tasikmalaya</option>
        <option value="Gratis" {{ $as=='Gratis'?'selected':'' }}>Gratis</option>
      </select>
    </div>

    <div>
      <label class="block text-sm">No. Asuransi</label>
      <input type="text" name="no_asuransi" class="border rounded w-full p-2"
             value="{{ old('no_asuransi', $visit->no_asuransi ?? '') }}">
    </div>
    <div>
      <label class="block text-sm">Kode Diagnosa (ICD-10)</label>
      <input type="text" name="kode_diagnosa" class="border rounded w-full p-2"
             placeholder="mis. I69.4"
             value="{{ old('kode_diagnosa', $visit->kode_diagnosa ?? '') }}">
    </div>
    <div>
      <label class="block text-sm">Diagnosa</label>
      <input type="text" name="diagnosa" class="border rounded w-full p-2"
             value="{{ old('diagnosa', $visit->diagnosa ?? '') }}">
    </div>
    <div>
      <label class="block text-sm">Jenis Kasus</label>
      <input type="text" name="jenis_kasus" class="border rounded w-full p-2"
             value="{{ old('jenis_kasus', $visit->jenis_kasus ?? '') }}">
    </div>
  </div>
</div>

<div class="mt-4">
  <button class="px-4 py-2 bg-blue-600 text-white rounded">{{ $isEdit ? 'Perbarui' : 'Simpan' }}</button>
  <a href="{{ route('visits.index') }}" class="px-4 py-2 bg-gray-200 rounded">Batal</a>
</div>

{{-- error validasi --}}
@if ($errors->any())
  <div class="mt-4 p-3 bg-red-50 text-red-700 rounded">
    <div class="font-semibold mb-1">Form belum valid:</div>
    <ul class="list-disc ml-6 text-sm">
      @foreach ($errors->all() as $e)
        <li>{{ $e }}</li>
      @endforeach
    </ul>
  </div>
@endif

{{-- ===== Loader Kec/Desa (pakai endpoint lokal kamu: ref.kecamatan & ref.desa) ===== --}}
<script>
async function loadKecamatan(selectId, selectedValue = '') {
  const sel = document.getElementById(selectId);
  sel.innerHTML = '<option value="">Memuat kecamatan...</option>';
  try {
    const res = await fetch(`{{ route('ref.kecamatan') }}`);
    const data = await res.json();
    sel.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
    for (const k of data) {
      const opt = document.createElement('option');
      opt.value = k.id; opt.textContent = k.nama;
      if (String(selectedValue) === String(k.id)) opt.selected = true;
      sel.appendChild(opt);
    }
  } catch (e) {
    console.error(e);
    sel.innerHTML = '<option value="">Gagal memuat kecamatan</option>';
  }
}

async function loadDesa(selectId, kecamatanId, selectedValue = '') {
  const sel = document.getElementById(selectId);
  if (!kecamatanId) { sel.innerHTML = '<option value="">-- Pilih Kecamatan dulu --</option>'; return; }
  sel.innerHTML = '<option value="">Memuat desa...</option>';
  try {
    const res = await fetch(`{{ route('ref.desa') }}?kecamatan_id=${encodeURIComponent(kecamatanId)}`);
    const data = await res.json();
    sel.innerHTML = '<option value="">-- Pilih Desa --</option>';
    for (const d of data) {
      const opt = document.createElement('option');
      opt.value = d.id; opt.textContent = d.nama;
      if (String(selectedValue) === String(d.id)) opt.selected = true;
      sel.appendChild(opt);
    }
  } catch (e) {
    console.error(e);
    sel.innerHTML = '<option value="">Gagal memuat desa</option>';
  }
}

// sinkronkan hidden "nama"
function syncNama(selectId, hiddenNamaId) {
  const sel = document.getElementById(selectId);
  const hid = document.getElementById(hiddenNamaId);
  hid.value = sel.selectedOptions[0]?.textContent ?? '';
}
document.addEventListener('change', e => {
  if (e.target.id === 'facility_kecamatan_id') {
    loadDesa('facility_desa_id', e.target.value, document.getElementById('facility_desa_id').dataset.selected || '');
    syncNama('facility_kecamatan_id','facility_kecamatan_nama');
  }
  if (e.target.id === 'facility_desa_id') syncNama('facility_desa_id','facility_desa_nama');

  if (e.target.id === 'patient_kecamatan_id') {
    loadDesa('patient_desa_id', e.target.value, document.getElementById('patient_desa_id').dataset.selected || '');
    syncNama('patient_kecamatan_id','patient_kecamatan_nama');
  }
  if (e.target.id === 'patient_desa_id') syncNama('patient_desa_id','patient_desa_nama');
});

// inisialisasi awal
document.addEventListener('DOMContentLoaded', async () => {
  await loadKecamatan('facility_kecamatan_id', document.getElementById('facility_kecamatan_id').dataset.selected || '');
  await loadKecamatan('patient_kecamatan_id', document.getElementById('patient_kecamatan_id').dataset.selected || '');

  // kalau ada kecamatan yang sudah terpilih (edit/old), muat desanya
  const fKid = document.getElementById('facility_kecamatan_id').value;
  const pKid = document.getElementById('patient_kecamatan_id').value;
  await loadDesa('facility_desa_id', fKid, document.getElementById('facility_desa_id').dataset.selected || '');
  await loadDesa('patient_desa_id', pKid, document.getElementById('patient_desa_id').dataset.selected || '');

  // set nama awal
  syncNama('facility_kecamatan_id','facility_kecamatan_nama');
  syncNama('facility_desa_id','facility_desa_nama');
  syncNama('patient_kecamatan_id','patient_kecamatan_nama');
  syncNama('patient_desa_id','patient_desa_nama');
});
</script>
