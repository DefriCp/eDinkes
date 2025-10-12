<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\District;

class DistrictSeeder extends Seeder {
    public function run(): void {
        $rows = [
            ['code_bps'=>'320101','name'=>'Kec. A','geojson_prop_key'=>'kode_bps'],
            ['code_bps'=>'320102','name'=>'Kec. B','geojson_prop_key'=>'kode_bps'],
            ['code_bps'=>'320103','name'=>'Kec. C','geojson_prop_key'=>'kode_bps'],
        ];
        foreach($rows as $r){ District::updateOrCreate(['code_bps'=>$r['code_bps']], $r); }
    }
}
