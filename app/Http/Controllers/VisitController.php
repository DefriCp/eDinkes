<?php

namespace App\Http\Controllers;

use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class VisitController extends Controller
{
    public function index()
    {
        $q = Visit::query();

        if ($ft = request('facility_type')) $q->where('facility_type', $ft);
        if ($fid = request('facility_id'))  $q->where('facility_id', $fid);
        if ($date = request('tanggal'))     $q->whereDate('tanggal', $date);

        $visits = $q->latest('tanggal')->paginate(15)->withQueryString();
        return view('visits.index', compact('visits'));
    }

    public function create()
    {
        return view('visits.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules());

        Visit::create($data);

        return redirect()
            ->route('visits.index')
            ->with('success', 'Kunjungan tersimpan.');
    }

    public function edit(Visit $visit)
    {
        return view('visits.edit', compact('visit'));
    }

    public function update(Request $request, Visit $visit)
    {
        $data = $request->validate($this->rules());

        $visit->update($data);

        return redirect()
            ->route('visits.index')
            ->with('success', 'Kunjungan diperbarui.');
    }

    public function destroy(Visit $visit)
    {
        $visit->delete();

        return redirect()
            ->route('visits.index')
            ->with('success', 'Kunjungan dihapus.');
    }
    private function rules(): array
    {
        return [
            'facility_type' => ['required', Rule::in(['puskesmas','posyandu'])],
            'facility_id'   => ['required','integer'],
            'tanggal'       => ['required','date'],
            'nama_pasien'   => ['required','string','max:255'],
            'no_erm'        => ['nullable','string','max:100'],
            'nik'           => ['nullable','string','max:40'],
            'no_rm_lama'    => ['nullable','string','max:100'],
            'no_dokumen_rm' => ['nullable','string','max:100'],
            'jenis_kelamin' => ['nullable', Rule::in(['L','P'])],
            'tempat_lahir'  => ['nullable','string','max:100'],
            'tanggal_lahir' => ['nullable','date'],
            'umur'          => ['nullable','integer','min:0','max:150'],
            'pekerjaan'     => ['nullable','string','max:100'],
            'alamat'            => ['nullable','string','max:500'],
            'agama'             => ['nullable','string','max:50'],
            'status_pernikahan' => ['nullable','string','max:50'],
            'facility_kecamatan_id'   => ['required','string','max:50'],
            'facility_kecamatan_nama' => ['nullable','string','max:100'],
            'facility_desa_id'        => ['sometimes','required_if:facility_type,posyandu','string','max:50'],
            'facility_desa_nama'      => ['nullable','string','max:100'],
            'patient_kecamatan_id'    => ['required','string','max:50'],
            'patient_kecamatan_nama'  => ['nullable','string','max:100'],
            'patient_desa_id'         => ['required','string','max:50'],
            'patient_desa_nama'       => ['nullable','string','max:100'],
            'nama_ayah'       => ['nullable','string','max:255'],
            'jenis_kunjungan' => ['nullable','string','max:100'],
            'kunjungan'       => ['nullable','string','max:100'],
            'poli'            => ['nullable','string','max:100'],
            'asuransi'        => ['nullable','string','max:100'],
            'no_asuransi'     => ['nullable','string','max:100'],
            'diagnosa'        => ['nullable','string','max:255'],
            'jenis_kasus'     => ['nullable','string','max:100'],
        ];
    }
}
