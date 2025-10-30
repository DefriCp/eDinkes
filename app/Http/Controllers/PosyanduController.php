<?php

namespace App\Http\Controllers;

use App\Models\Posyandu;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\PosyanduImport;

class PosyanduController extends Controller
{
    public function index(Request $r)
    {
        $q = $r->get('q');
        $rows = Posyandu::select('id','nama_posyandu','nama_puskesmas','desa','kecamatan','status_posyandu_aktif')
            ->when($q, function($w) use ($q){
                $like = "%{$q}%";
                $w->where(fn($x)=>$x->where('nama_posyandu','like',$like)
                    ->orWhere('nama_puskesmas','like',$like)
                    ->orWhere('desa','like',$like)
                    ->orWhere('kecamatan','like',$like));
            })
            ->orderBy('nama_posyandu')
            ->paginate(20)
            ->withQueryString();

        return view('posyandu.index', compact('rows','q'));
    }

    public function create()  { return view('posyandu.create'); }
    public function show(Posyandu $posyandu) { return view('posyandu.show',['row'=>$posyandu]); }
    public function edit(Posyandu $posyandu) { return view('posyandu.edit',['row'=>$posyandu]); }

    public function store(Request $r)
    {
        Posyandu::create($this->rules($r));
        return redirect()->route('posyandu.index')->with('ok','Data posyandu ditambahkan');
    }

    public function update(Request $r, Posyandu $posyandu)
    {
        $posyandu->update($this->rules($r));
        return redirect()->route('posyandu.index')->with('ok','Data posyandu diperbarui');
    }

    public function destroy(Posyandu $posyandu)
    {
        $posyandu->delete();
        return back()->with('ok','Data posyandu dihapus');
    }

    /** ---------- IMPORT ---------- */
    public function importForm()
    {
        return view('posyandu.import');
    }

    public function importStore(Request $r)
    {
        $r->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:20480', // max 20MB
        ]);

        // Guard runtime (kalau php.ini tidak terbaca oleh server)
        @set_time_limit(0);
        @ini_set('max_execution_time', '0');
        @ini_set('memory_limit', '1024M');

        Excel::import(new PosyanduImport, $r->file('file'));

        return redirect()->route('posyandu.index')->with('ok','Import posyandu berhasil');
    }

    private function rules(Request $r): array
    {
        return $r->validate([
            'id_posyandu' => 'nullable|string|max:100',
            'provinsi' => 'nullable|string|max:150',
            'kabupaten' => 'nullable|string|max:150',
            'kecamatan' => 'nullable|string|max:150',
            'kode_desa' => 'nullable|string|max:50',
            'desa' => 'nullable|string|max:150',
            'noregistrasi' => 'nullable|string|max:150',
            'nama_puskesmas' => 'nullable|string|max:200',
            'nama_posyandu' => 'nullable|string|max:200',
            'kriteria_1' => 'nullable|string|max:150',
            'kriteria_2' => 'nullable|string|max:150',
            'kriteria_3' => 'nullable|string|max:150',
            'status_posyandu_aktif' => 'nullable|string|max:50',
            'kriteria_siklus_hidup_1' => 'nullable|string|max:150',
            'kriteria_siklus_hidup_2' => 'nullable|string|max:150',
            'kriteria_siklus_hidup_3' => 'nullable|string|max:150',
            'status_posyandu_siklus_hidup' => 'nullable|string|max:50',
        ]);
    }
}
