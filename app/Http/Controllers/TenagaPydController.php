<?php

namespace App\Http\Controllers;

use App\Models\Pyd;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\PydImport;

class TenagaPydController extends Controller
{
    public function index(Request $r)
    {
        $q = $r->get('q');

        // Dashboard ringkas: hanya kolom yang dibutuhkan
        $rows = Pyd::select(['id','nama_kader','nama_posyandu','jenis_kelamin','status_pelatihan_posyandu'])
            ->when($q, function ($w) use ($q) {
                $like = "%{$q}%";
                $w->where(fn($x) => $x->where('nama_kader','like',$like)
                    ->orWhere('nama_posyandu','like',$like));
            })
            ->orderBy('nama_kader')
            ->paginate(25)
            ->withQueryString();

        return view('tenagapyd.index', ['rows'=>$rows, 'q'=>$q]);
    }

    public function create()
    {
        return view('tenagapyd.create');
    }

    public function store(Request $r)
    {
        Pyd::create($this->rules($r));
        return redirect()->route('tenagapyd.index')->with('ok','Data ditambahkan');
    }

    public function show(Pyd $tenagapyd)
    {
        // Route model binding → $tenagapyd = 1 orang lengkap
        return view('tenagapyd.show', ['row' => $tenagapyd]);
    }

    public function edit(Pyd $tenagapyd)
    {
        return view('tenagapyd.edit', ['row'=>$tenagapyd]);
    }

    public function update(Request $r, Pyd $tenagapyd)
    {
        $tenagapyd->update($this->rules($r));
        return redirect()->route('tenagapyd.index')->with('ok','Data diperbarui');
    }

    public function destroy(Pyd $tenagapyd)
    {
        $tenagapyd->delete();
        return back()->with('ok','Data dihapus');
    }

    public function importForm()
    {
        return view('tenagapyd.import');
    }

    public function importStore(Request $r)
    {
        $r->validate(['file'=>'required|file|mimes:xlsx,xls,csv|max:20480']);

        // Guard tambahan (kalau php.ini belum terbaca)
        @set_time_limit(0);
        @ini_set('max_execution_time', '0');
        @ini_set('memory_limit', '1024M');

        Excel::import(new PydImport, $r->file('file')); // PydImport sudah chunk/batch

        return redirect()->route('tenagapyd.index')->with('ok','Import berhasil');
    }

    private function rules(Request $r): array
    {
        return $r->validate([
            'provinsi' => 'nullable|string|max:150',
            'kabupaten' => 'nullable|string|max:150',
            'kecamatan' => 'nullable|string|max:150',
            'noregistrasi' => 'nullable|string|max:150',
            'nama_puskesmas' => 'nullable|string|max:200',
            'nama_posyandu' => 'nullable|string|max:200',
            'nama_kader' => 'nullable|string|max:200',
            'usia' => 'nullable|integer|min:0|max:120',
            'jenis_kelamin' => 'nullable|in:L,P',
            'nomor_sk' => 'nullable|string|max:200',
            'jenjang_pendidikan' => 'nullable|string|max:150',
            'alamat_ktp' => 'nullable|string',
            'alamat_domisili' => 'nullable|string',
            'kepemilikan_jkn' => 'nullable|string|max:150',
            'metode_koordinasi' => 'nullable|string|max:150',

            'status_pelatihan_posyandu' => 'nullable|string|max:150',
            'status_penilaian_posyandu' => 'nullable|string|max:150',
            'status_keterampilan_posyandu' => 'nullable|string|max:150',

            'status_pelatihan_bayi_balita' => 'nullable|string|max:150',
            'status_penilaian_bayi_balita' => 'nullable|string|max:150',
            'status_keterampilan_bayi_balita' => 'nullable|string|max:150',

            'status_pelatihan_ibu_hamil' => 'nullable|string|max:150',
            'status_penilaian_ibu_hamil' => 'nullable|string|max:150',
            'status_keterampilan_ibu_hamil' => 'nullable|string|max:150',

            'status_pelatihan_remaja' => 'nullable|string|max:150',
            'status_penilaian_remaja' => 'nullable|string|max:150',
            'status_keterampilan_remaja' => 'nullable|string|max:150',

            'status_pelatihan_lansia' => 'nullable|string|max:150',
            'status_penilaian_lansia' => 'nullable|string|max:150',
            'status_keterampilan_lansia' => 'nullable|string|max:150',

            'status_pelatihan_timbang_ukur' => 'nullable|string|max:150',
            'status_penilaian_timbang_ukur' => 'nullable|string|max:150',

            'tingkatan_kader' => 'nullable|string|max:150',
            'sudah_mengikuti_25_keterampilan_dasar' => 'nullable|boolean',
            'sudah_dinilai_keterampilan_dasar' => 'nullable|boolean',
        ]);
    }
}
