<?php

namespace App\Http\Controllers;

use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\VisitsImport;

class VisitController extends Controller
{
    public function index()
    {
        $visits = Visit::latest('tanggal')->paginate(15);
        return view('visits.index', compact('visits'));
    }

    public function create()
    {
        return view('visits.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules());
        $data = $this->normalize($data);

        Visit::create($data);
        return redirect()->route('visits.index')->with('success','Kunjungan tersimpan.');
    }

    public function edit(Visit $visit)
    {
        return view('visits.edit', compact('visit'));
    }

    public function update(Request $request, Visit $visit)
    {
        $data = $request->validate($this->rules());
        $data = $this->normalize($data);

        $visit->update($data);
        return redirect()->route('visits.index')->with('success','Kunjungan diperbarui.');
    }

    public function destroy(Visit $visit)
    {
        $visit->delete();
        return redirect()->route('visits.index')->with('success','Kunjungan dihapus.');
    }

    // ===== IMPORT =====
    public function importForm()
    {
        return view('visits.import');
    }

    public function importStore(Request $request)
    {
        $validated = $request->validate([
            'file'          => ['required','file','mimes:xlsx,csv'],
            'facility_type' => ['required', Rule::in(['puskesmas','puskesmas_pembantu','posyandu'])],
            'facility_id'   => ['nullable','integer'],
        ]);

        $context = [
            'facility_type' => $validated['facility_type'],
            'facility_id'   => $validated['facility_id'] ?? 0,
        ];

        $import = new VisitsImport($context);

        Excel::import($import, $validated['file']);
        $report = $import->getReport();

        return back()
            ->with('success', "Import selesai. Sukses: {$report['success']}, Gagal: {$report['failed']}")
            ->with('import_errors', $report['errors']);
    }
    // ===== END IMPORT =====

    private function rules(): array
    {
        return [
            'facility_type' => ['required', Rule::in(['puskesmas','puskesmas_pembantu','posyandu'])],
            'facility_id'   => ['nullable','integer'],
            'tanggal'       => ['required','date'],

            'facility_kecamatan_id'   => ['nullable','string','max:50'],
            'facility_kecamatan_nama' => ['nullable','string','max:100'],
            'facility_desa_id'        => ['required_if:facility_type,posyandu','nullable','string','max:50'],
            'facility_desa_nama'      => ['nullable','string','max:100'],

            'asuransi'      => ['nullable','string','max:100'],
            'no_asuransi'   => ['nullable','string','max:100'],
            'kode_diagnosa' => ['nullable','string','max:20'],

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

            'alamat'                 => ['nullable','string','max:500'],
            'agama'                  => ['nullable','string','max:50'],
            'status_pernikahan'      => ['nullable','string','max:50'],
            'patient_kecamatan_id'   => ['nullable','string','max:50'],
            'patient_kecamatan_nama' => ['nullable','string','max:100'],
            'patient_desa_id'        => ['nullable','string','max:50'],
            'patient_desa_nama'      => ['nullable','string','max:100'],
            'nama_ayah'              => ['nullable','string','max:255'],

            'jenis_kunjungan' => ['nullable','string','max:100'],
            'kunjungan'       => ['nullable','string','max:100'],
            'poli'            => ['nullable','string','max:100'],
            'diagnosa'        => ['nullable','string','max:255'],
            'jenis_kasus'     => ['nullable','string','max:100'],
        ];
    }

    private function normalize(array $data): array
    {
        $data['facility_id'] = $data['facility_id'] ?? null;

        if (($data['facility_type'] ?? null) !== 'posyandu') {
            $data['facility_desa_id']   = null;
            $data['facility_desa_nama'] = null;
        }

        $cleanNama = function($id, $nama) {
            if (empty($id)) return null;
            if (is_string($nama) && str_starts_with($nama, '-- ')) return null;
            return $nama;
        };

        $data['facility_kecamatan_nama'] = $cleanNama($data['facility_kecamatan_id'] ?? null, $data['facility_kecamatan_nama'] ?? null);
        $data['facility_desa_nama']      = $cleanNama($data['facility_desa_id'] ?? null, $data['facility_desa_nama'] ?? null);
        $data['patient_kecamatan_nama']  = $cleanNama($data['patient_kecamatan_id'] ?? null, $data['patient_kecamatan_nama'] ?? null);
        $data['patient_desa_nama']       = $cleanNama($data['patient_desa_id'] ?? null, $data['patient_desa_nama'] ?? null);

        return $data;
    }
}
