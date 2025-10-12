<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\AreaMetric;
use App\Models\District;

class AreaMetricSeeder extends Seeder {
    public function run(): void {
        $year = 2025; $month = 10;
        foreach (District::all() as $d) {
            AreaMetric::updateOrCreate(
                ['district_id'=>$d->id,'month'=>$month,'year'=>$year],
                ['idl_pct'=>rand(60,95), 'k1_pct'=>rand(60,95), 'k4_pct'=>rand(50,90),
                 'dbd_cases'=>rand(0,50), 'visits'=>rand(500,5000)]
            );
        }
    }
}
