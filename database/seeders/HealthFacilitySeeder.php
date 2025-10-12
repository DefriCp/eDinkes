<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\HealthFacility;
use App\Models\District;

class HealthFacilitySeeder extends Seeder {
    public function run(): void {
        $a = District::where('code_bps','320101')->first();
        $b = District::where('code_bps','320102')->first();

        $rows = [
            ['district_id'=>$a?->id,'name'=>'Puskesmas Kec. A','type'=>'Puskesmas','address'=>'Jl. Utama 1','lat'=>-7.350000,'lng'=>108.220000],
            ['district_id'=>$b?->id,'name'=>'Pustu Desa B1','type'=>'PUSTU','address'=>'Dusun Barat','lat'=>-7.360000,'lng'=>108.230000],
        ];
        foreach($rows as $r){ HealthFacility::create($r); }
    }
}
