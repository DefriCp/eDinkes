<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $month = (int) request('month', now()->month);
        $year  = (int) request('year',  now()->year);

        $prevMonth = $month === 1 ? 12 : $month - 1;
        $prevYear  = $month === 1 ? $year - 1 : $year;

        $currentDiag = DB::table('monthly_diagnoses')
            ->select('code','name','total_cases')
            ->where('year', $year)
            ->where('month', $month)
            ->orderByDesc('total_cases')
            ->limit(10)
            ->get();

        $codes = $currentDiag->pluck('code')->all();

        $prevDiagMap = DB::table('monthly_diagnoses')
            ->select('code','total_cases')
            ->where('year', $prevYear)
            ->where('month', $prevMonth)
            ->whereIn('code', $codes)
            ->get()
            ->keyBy('code');

        $diagnoses = $currentDiag->map(function ($r) use ($prevDiagMap) {
            return (object) [
                'code' => $r->code,
                'name' => $r->name,
                'prev' => (int) ($prevDiagMap[$r->code]->total_cases ?? 0),
                'curr' => (int) $r->total_cases,
            ];
        });

        // Top-10 Obat (bulan terpilih)
        $currentDrugs = DB::table('monthly_drug_usage')
            ->select('drug_name','total_usage')
            ->where('year', $year)
            ->where('month', $month)
            ->orderByDesc('total_usage')
            ->limit(10)
            ->get();

        $names = $currentDrugs->pluck('drug_name')->all();

        $prevDrugMap = DB::table('monthly_drug_usage')
            ->select('drug_name','total_usage')
            ->where('year', $prevYear)
            ->where('month', $prevMonth)
            ->whereIn('drug_name', $names)
            ->get()
            ->keyBy('drug_name');

        $drugs = $currentDrugs->map(function ($r) use ($prevDrugMap) {
            return (object) [
                'name' => $r->drug_name,
                'prev' => (int) ($prevDrugMap[$r->drug_name]->total_usage ?? 0),
                'curr' => (int) $r->total_usage,
            ];
        });

        return view('dashboard.top10', compact('diagnoses','drugs','month','year','prevMonth','prevYear'));
    }
}
