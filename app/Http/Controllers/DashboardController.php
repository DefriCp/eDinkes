<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\Visit;

class DashboardController extends Controller
{
    public function index()
    {
        // -----------------------------
        // Filter bulan & tahun
        // -----------------------------
        $month = (int) request('month', now()->month);
        $year  = (int) request('year',  now()->year);
        $prevMonth = $month === 1 ? 12 : $month - 1;
        $prevYear  = $month === 1 ? $year - 1 : $year;

        // -----------------------------
        // Kunjungan bulan ini & lalu
        // -----------------------------
        $totalVisitsMonth = Visit::whereYear('tanggal', $year)
            ->whereMonth('tanggal', $month)
            ->count();

        $totalVisitsPrev = Visit::whereYear('tanggal', $prevYear)
            ->whereMonth('tanggal', $prevMonth)
            ->count();

        // -----------------------------
        // Tujuan fasilitas (3 kategori)
        // -----------------------------
        $puskesmasCount = Visit::whereYear('tanggal', $year)
            ->whereMonth('tanggal', $month)
            ->where('facility_type', 'puskesmas')
            ->count();

        $puskesmasPembantuCount = Visit::whereYear('tanggal', $year)
            ->whereMonth('tanggal', $month)
            ->where('facility_type', 'puskesmas_pembantu')
            ->count();

        $posyanduCount = Visit::whereYear('tanggal', $year)
            ->whereMonth('tanggal', $month)
            ->where('facility_type', 'posyandu')
            ->count();

        // -----------------------------
        // Diagnosa terbanyak (data asli visits)
        // -----------------------------
        $topDiagnosesFromVisits = Visit::whereYear('tanggal', $year)
            ->whereMonth('tanggal', $month)
            ->select('diagnosa', DB::raw('COUNT(*) as total'))
            ->groupBy('diagnosa')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        // -----------------------------
        // Grafik kunjungan per bulan (line)
        // -----------------------------
        $lineLabels = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
        $lineData   = array_fill(0, 12, 0);

        $byMonth = Visit::selectRaw('MONTH(tanggal) as m, COUNT(*) as c')
            ->whereYear('tanggal', $year)
            ->groupBy('m')
            ->pluck('c','m');

        for ($i=1; $i<=12; $i++) {
            $lineData[$i-1] = (int) ($byMonth[$i] ?? 0);
        }

        // -----------------------------
        // Top-10 Diagnosa & Obat (jika tabel agregat ada)
        // -----------------------------
        $diagnoses = collect();
        if (Schema::hasTable('monthly_diagnoses')) {
            $curr = DB::table('monthly_diagnoses')
                ->select('code','name','total_cases')
                ->where('year', $year)->where('month', $month)
                ->orderByDesc('total_cases')->limit(10)->get();

            $codes = $curr->pluck('code')->all();

            $prevMap = DB::table('monthly_diagnoses')
                ->select('code','total_cases')
                ->where('year', $prevYear)->where('month', $prevMonth)
                ->whereIn('code', $codes)->get()->keyBy('code');

            $diagnoses = $curr->map(fn($r) => (object) [
                'code' => $r->code,
                'name' => $r->name,
                'curr' => (int) $r->total_cases,
                'prev' => (int) ($prevMap[$r->code]->total_cases ?? 0),
            ]);
        }

        $drugs = collect();
        if (Schema::hasTable('monthly_drug_usage')) {
            $curr = DB::table('monthly_drug_usage')
                ->select('drug_name','total_usage')
                ->where('year', $year)->where('month', $month)
                ->orderByDesc('total_usage')->limit(10)->get();

            $names = $curr->pluck('drug_name')->all();

            $prevMap = DB::table('monthly_drug_usage')
                ->select('drug_name','total_usage')
                ->where('year', $prevYear)->where('month', $prevMonth)
                ->whereIn('drug_name', $names)->get()->keyBy('drug_name');

            $drugs = $curr->map(fn($r) => (object) [
                'name' => $r->drug_name,
                'curr' => (int) $r->total_usage,
                'prev' => (int) ($prevMap[$r->drug_name]->total_usage ?? 0),
            ]);
        }

        return view('dashboard.index', [
            // waktu
            'month' => $month, 'year' => $year,
            'prevMonth' => $prevMonth, 'prevYear' => $prevYear,

            // ringkasan
            'totalVisitsMonth' => $totalVisitsMonth,
            'totalVisitsPrev'  => $totalVisitsPrev,
            'puskesmasCount'   => $puskesmasCount,
            'puskesmasPembantuCount' => $puskesmasPembantuCount,
            'posyanduCount'    => $posyanduCount,

            // chart line
            'lineLabels' => $lineLabels,
            'lineData'   => $lineData,

            // tabel top 10 (agregat bila ada)
            'diagnoses'  => $diagnoses,
            'drugs'      => $drugs,

            // tabel top 10 (dari visits asli)
            'topDiagnosesFromVisits' => $topDiagnosesFromVisits,
        ]);
    }
}
