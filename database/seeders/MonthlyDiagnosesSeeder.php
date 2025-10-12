<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\MonthlyDiagnosis;

class MonthlyDiagnosesSeeder extends Seeder {
    public function run(): void {
        $year = 2025;
        $rows = [
            ['I10','Essential (primary) hypertension', 3240, 765],
            ['J06.9','Acute upper respiratory infection, unspecified', 3929, 739],
            ['K30','Dyspepsia', 2539, 498],
            ['J00','Acute nasopharyngitis [common cold]', 2833, 430],
            ['R50.9','Fever, unspecified', 1394, 362],
            ['M79.1','Myalgia', 1424, 260],
            ['E11','Non-insulin-dependent diabetes mellitus', 966, 168],
            ['K29','Gastritis and duodenitis', 537, 163],
            ['R51','Headache', 525, 163],
            ['J06','Acute upper respiratory infections of multiple and unspecified sites', 1107, 159],
        ];
        foreach ($rows as [$code,$name,$sept,$oct]) {
            MonthlyDiagnosis::create(['code'=>$code,'name'=>$name,'month'=>9,'year'=>$year,'total_cases'=>$sept]);
            MonthlyDiagnosis::create(['code'=>$code,'name'=>$name,'month'=>10,'year'=>$year,'total_cases'=>$oct]);
        }
    }
}
