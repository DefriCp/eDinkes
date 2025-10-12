@csrf
@php $isEdit = isset($visit); @endphp

<div class="grid md:grid-cols-2 gap-4">
  {{-- ================= FASILITAS & TANGGAL ================= --}}
  <div class="card p-4">
    <h3 class="font-semibold mb-3">Fasilitas & Tanggal</h3>

    <label class="block mb-2 text-sm">Jenis Fasilitas</label>
    <select name="facility_type" id="facility_type" class="border rounded w-full p-2" required>
      <option value="">-- Pilih --</option>
      <option value="puskesmas" {{ old('facility_type', $visit->facility_type ?? '')=='puskesmas'?'selected':'' }}>Puskesmas</option>
      <option value="puskesmas" {{ old('facility_type', $visit->facility_type ?? '')=='puskesmas pembantu'?'selected':'' }}>Puskesmas Pembantu</option>
      <option value="posyandu"  {{ old('facility_type', $visit->facility_type ?? '')=='posyandu'?'selected':'' }}>Posyandu</option>
    </select>

    <label class="block mt-3 mb-2 text-sm">Jenis Asuransi</label>
    <select name="facility_id" id="facility_id" class="border rounded w-full p-2" required>
      <option value="">-- Pilih jenis asuransi dulu --</option>
      <option value="">UMUM</option>
      <option value="">BPJS Kesehatan</option>
      <option value="">Pemerintah Daerah Kabupaten Tasikmalaya</option>
      <option value="">Gratis</option>
    </select>

    <div class="grid grid-cols-2 gap-3 mt-3">
      <div>
        <label class="block text-sm">Kecamatan (Fasilitas)</label>
        <select id="f_kecamatan_id" name="facility_kecamatan_id" class="border rounded w-full p-2" required>
          <option value="">-- Pilih Kecamatan --</option>
        </select>
      </div>
      <div>
        <label class="block text-sm">Desa/Kelurahan (Fasilitas)</label>
        <select id="f_desa_id" name="facility_desa_id" class="border rounded w-full p-2">
          <option value="">-- Pilih Kecamatan dulu --</option>
        </select>
      </div>
    </div>

    <input type="hidden" name="facility_kecamatan_nama" id="f_kecamatan_nama" value="{{ old('facility_kecamatan_nama', $visit->facility_kecamatan_nama ?? '') }}">
    <input type="hidden" name="facility_desa_nama" id="f_desa_nama" value="{{ old('facility_desa_nama', $visit->facility_desa_nama ?? '') }}">

    <label class="block mt-3 mb-2 text-sm">Tanggal Kunjungan</label>
    <input type="date" name="tanggal" class="border rounded w-full p-2" value="{{ old('tanggal', $visit->tanggal ?? now()->toDateString()) }}" required>
  </div>

  {{-- ================= IDENTITAS PASIEN ================= --}}
  <div class="card p-4">
    <h3 class="font-semibold mb-3">Identitas Pasien</h3>

    <div class="grid grid-cols-2 gap-3">
      <div>
        <label class="block text-sm">Nama Pasien</label>
        <input type="text" name="nama_pasien" value="{{ old('nama_pasien', $visit->nama_pasien ?? '') }}" class="border rounded w-full p-2" required>
      </div>
      <div>
        <label class="block text-sm">Jenis Kelamin</label>
        <select name="jenis_kelamin" class="border rounded w-full p-2">
          <option value="">-</option>
          <option value="L" {{ old('jenis_kelamin', $visit->jenis_kelamin ?? '')=='L'?'selected':'' }}>L</option>
          <option value="P" {{ old('jenis_kelamin', $visit->jenis_kelamin ?? '')=='P'?'selected':'' }}>P</option>
        </select>
      </div>
      <div>
        <label class="block text-sm">Tempat Lahir</label>
        <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $visit->tempat_lahir ?? '') }}" class="border rounded w-full p-2">
      </div>
      <div>
        <label class="block text-sm">Tanggal Lahir</label>
        <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $visit->tanggal_lahir ?? '') }}" class="border rounded w-full p-2">
      </div>
      <div>
        <label class="block text-sm">Umur</label>
        <input type="number" min="0" max="150" name="umur" value="{{ old('umur', $visit->umur ?? '') }}" class="border rounded w-full p-2">
      </div>
      <div>
        <label class="block text-sm">Pekerjaan</label>
        <input type="text" name="pekerjaan" value="{{ old('pekerjaan', $visit->pekerjaan ?? '') }}" class="border rounded w-full p-2">
      </div>
      <div>
        <label class="block text-sm">No. eRM</label>
        <input type="text" name="no_erm" value="{{ old('no_erm', $visit->no_erm ?? '') }}" class="border rounded w-full p-2">
      </div>
      <div>
        <label class="block text-sm">NIK</label>
        <input type="text" name="nik" value="{{ old('nik', $visit->nik ?? '') }}" class="border rounded w-full p-2">
      </div>
      <div>
        <label class="block text-sm">No. RM Lama</label>
        <input type="text" name="no_rm_lama" value="{{ old('no_rm_lama', $visit->no_rm_lama ?? '') }}" class="border rounded w-full p-2">
      </div>
      <div>
        <label class="block text-sm">No. Dokumen RM</label>
        <input type="text" name="no_dokumen_rm" value="{{ old('no_dokumen_rm', $visit->no_dokumen_rm ?? '') }}" class="border rounded w-full p-2">
      </div>
    </div>
  </div>

  {{-- ================= ALAMAT PASIEN ================= --}}
  <div class="card p-4">
    <h3 class="font-semibold mb-3">Alamat Pasien</h3>
    <label class="block text-sm">Alamat</label>
    <input type="text" name="alamat" value="{{ old('alamat', $visit->alamat ?? '') }}" class="border rounded w-full p-2">

    <div class="grid grid-cols-2 gap-3 mt-3">
      <div>
        <label class="block text-sm">Kecamatan (Pasien)</label>
        <select id="p_kecamatan_id" name="patient_kecamatan_id" class="border rounded w-full p-2" required>
          <option value="">-- Pilih Kecamatan --</option>
        </select>
      </div>
      <div>
        <label class="block text-sm">Desa/Kelurahan (Pasien)</label>
        <select id="p_desa_id" name="patient_desa_id" class="border rounded w-full p-2" required>
          <option value="">-- Pilih Kecamatan dulu --</option>
        </select>
      </div>
    </div>

    <input type="hidden" name="patient_kecamatan_nama" id="p_kecamatan_nama" value="{{ old('patient_kecamatan_nama', $visit->patient_kecamatan_nama ?? '') }}">
    <input type="hidden" name="patient_desa_nama" id="p_desa_nama" value="{{ old('patient_desa_nama', $visit->patient_desa_nama ?? '') }}">
  </div>

  {{-- ================= PELAYANAN ================= --}}
  <div class="card p-4">
    <h3 class="font-semibold mb-3">Pelayanan</h3>

    <div class="grid grid-cols-2 gap-3">
      <div>
        <label class="block text-sm">Jenis Kunjungan</label>
        <input type="text" name="jenis_kunjungan" value="{{ old('jenis_kunjungan', $visit->jenis_kunjungan ?? '') }}" class="border rounded w-full p-2">
      </div>
      <div>
        <label class="block text-sm">Kunjungan</label>
        <input type="text" name="kunjungan" value="{{ old('kunjungan', $visit->kunjungan ?? '') }}" class="border rounded w-full p-2">
      </div>
      <div>
        <label class="block text-sm">Poli/Ruangan</label>
        <input type="text" name="poli" value="{{ old('poli', $visit->poli ?? '') }}" class="border rounded w-full p-2">
      </div>
      <div>
        <label class="block text-sm">Asuransi</label>
        <input type="text" name="asuransi" value="{{ old('asuransi', $visit->asuransi ?? '') }}" class="border rounded w-full p-2">
      </div>
      <div>
        <label class="block text-sm">No. Asuransi</label>
        <input type="text" name="no_asuransi" value="{{ old('no_asuransi', $visit->no_asuransi ?? '') }}" class="border rounded w-full p-2">
      </div>
      <div class="col-span-2">
        <label class="block text-sm">Diagnosa</label>
        <input type="text" name="diagnosa" value="{{ old('diagnosa', $visit->diagnosa ?? '') }}" class="border rounded w-full p-2">
      </div>
      <div>
        <label class="block text-sm">Jenis Kasus</label>
        <input type="text" name="jenis_kasus" value="{{ old('jenis_kasus', $visit->jenis_kasus ?? '') }}" class="border rounded w-full p-2">
      </div>
    </div>
  </div>
</div>

<div class="mt-4">
  <button class="px-4 py-2 bg-blue-600 text-white rounded">{{ $isEdit ? 'Perbarui' : 'Simpan' }}</button>
  <a href="{{ route('visits.index') }}" class="px-4 py-2 bg-gray-200 rounded">Batal</a>
</div>

{{-- ================= SCRIPT ================= --}}
<script>
async function loadKecamatan(selectId, selectedId = null){
  const sel = document.getElementById(selectId);
  sel.innerHTML = '<option value="">Memuat kecamatan...</option>';
  try {
    const res  = await fetch(`{{ route('ref.kecamatan') }}`);
    if(!res.ok) throw new Error('HTTP '+res.status);
    const data = await res.json();
    sel.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
    data.forEach(k => {
      const opt = document.createElement('option');
      opt.value = k.id; opt.textContent = k.nama;
      if (selectedId && String(selectedId) === String(k.id)) opt.selected = true;
      sel.appendChild(opt);
    });
  } catch(e){
    console.error('loadKecamatan', selectId, e);
    sel.innerHTML = '<option value="">Gagal memuat kecamatan</option>';
  }
}

async function loadDesa(kecamatanId, selectId, selectedId = null){
  const sel = document.getElementById(selectId);
  if(!kecamatanId){ sel.innerHTML = '<option value="">-- Pilih Kecamatan dulu --</option>'; return; }
  sel.innerHTML = '<option value="">Memuat desa...</option>';
  try {
    const res  = await fetch(`{{ route('ref.desa') }}?kecamatan_id=${encodeURIComponent(kecamatanId)}`);
    if(!res.ok) throw new Error('HTTP '+res.status);
    const data = await res.json();
    sel.innerHTML = '<option value="">-- Pilih Desa --</option>';
    data.forEach(d => {
      const opt = document.createElement('option');
      opt.value = d.id; opt.textContent = d.nama;
      if (selectedId && String(selectedId) === String(d.id)) opt.selected = true;
      sel.appendChild(opt);
    });
  } catch(e){
    console.error('loadDesa', selectId, e);
    sel.innerHTML = '<option value="">Gagal memuat desa</option>';
  }
}


function applyFacilityDesaRule(){
  const type      = document.getElementById('facility_type').value;
  const desaSel   = document.getElementById('f_desa_id');
  if(type === 'posyandu'){
    desaSel.disabled = false;
    desaSel.setAttribute('required','required');
  }else{
    desaSel.disabled = true;
    desaSel.removeAttribute('required');
    desaSel.innerHTML = '<option value="">-- Tidak perlu memilih desa --</option>';
    document.getElementById('f_desa_nama').value = '';
  }
}

document.addEventListener('DOMContentLoaded', () => {
  const fKecSel = document.getElementById('f_kecamatan_id');
  const fDesaSel= document.getElementById('f_desa_id');
  const pKecSel = document.getElementById('p_kecamatan_id');
  const pDesaSel= document.getElementById('p_desa_id');

  const fKecName = document.getElementById('f_kecamatan_nama');
  const fDesaName= document.getElementById('f_desa_nama');
  const pKecName = document.getElementById('p_kecamatan_nama');
  const pDesaName= document.getElementById('p_desa_nama');

  const pre = {
    type:  @json(old('facility_type', $visit->facility_type ?? '')),
    fid:   @json(old('facility_id',   $visit->facility_id ?? '')),
    fKec:  @json(old('facility_kecamatan_id', $visit->facility_kecamatan_id ?? '')),
    fDesa: @json(old('facility_desa_id',      $visit->facility_desa_id ?? '')),
    pKec:  @json(old('patient_kecamatan_id',  $visit->patient_kecamatan_id ?? '')),
    pDesa: @json(old('patient_desa_id',       $visit->patient_desa_id ?? '')),
  };

  if (pre.type) {
    loadFacilities(pre.type, pre.fid);
  }

  loadKecamatan('f_kecamatan_id', pre.fKec).then(()=>{
    if (pre.fKec) loadDesa(pre.fKec, 'f_desa_id', pre.fDesa);
  });
  loadKecamatan('p_kecamatan_id', pre.pKec).then(()=>{
    if (pre.pKec) loadDesa(pre.pKec, 'p_desa_id', pre.pDesa);
  });

  applyFacilityDesaRule();
  document.getElementById('facility_type').addEventListener('change', e=>{
    applyFacilityDesaRule();
    loadFacilities(e.target.value);
  });

  fKecSel.addEventListener('change', e=>{
    fKecName.value = e.target.selectedOptions[0]?.text || '';
    loadDesa(e.target.value, 'f_desa_id');
  });
  fDesaSel.addEventListener('change', e=>{
    fDesaName.value = e.target.selectedOptions[0]?.text || '';
  });

  pKecSel.addEventListener('change', e=>{
    pKecName.value = e.target.selectedOptions[0]?.text || '';
    loadDesa(e.target.value, 'p_desa_id');
  });
  pDesaSel.addEventListener('change', e=>{
    pDesaName.value = e.target.selectedOptions[0]?.text || '';
  });
});

async function loadFacilities(type, selectedId = null){
  const facSel = document.getElementById('facility_id');
  if (!type) {
    facSel.innerHTML = '<option value="">-- Pilih jenis fasilitas dulu --</option>';
    return;
  }
  facSel.innerHTML = '<option value="">Memuat fasilitas...</option>';
  try {
    const res = await fetch(`{{ url('/api/facilities') }}?type=${encodeURIComponent(type)}`);
    if (!res.ok) throw new Error('HTTP '+res.status);
    const data = await res.json();
    facSel.innerHTML = '<option value="">-- Pilih Fasilitas --</option>';
    data.forEach(it => {
      const opt = document.createElement('option');
      opt.value = it.id; opt.textContent = it.name;
      if (selectedId && String(selectedId) === String(it.id)) opt.selected = true;
      facSel.appendChild(opt);
    });
  } catch(e){
    console.error('loadFacilities', e);
    facSel.innerHTML = '<option value="">Gagal memuat fasilitas</option>';
  }
}
</script>
