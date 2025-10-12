<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MonthlyDrugUsage;

class MonthlyDrugUsageSeeder extends Seeder
{
    public function run(): void
    {
            $year = 2025;
        $rows = [
            ['Paracetamol Tablet 500 mg',              21897, 5311],
            ['Antasida DOEN Tablet',                   10062, 2956],
            ['Paracetamol tab 500 mg JKN',             10781, 1675],
            ['Paracetamol tab 500 mg DAK',              6528, 1636],
            ['Amoksisilin 500 mg Kaplet',               4801, 1443],
            ['Paracetamol 650 mg Tablet (FASGO FORTE)', 3985, 1430],
            ['Paracetamol tab 500 mg',                 10783, 1427],
            ['ambroxol tablet 30 mg JKN',               4394, 1399],
            ['Vitamin B Kompleks Tablet JKN',           5566, 1328],
            ['Flutamol Tab - JKN',                         0, 1265],
        ];
        foreach ($rows as [$name,$sept,$oct]) {
            MonthlyDrugUsage::create(['drug_name'=>$name,'month'=>9,'year'=>2025,'total_usage'=>$sept]);
            MonthlyDrugUsage::create(['drug_name'=>$name,'month'=>10,'year'=>2025,'total_usage'=>$oct]);
        }
    }
}
