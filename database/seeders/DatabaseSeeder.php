<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {
    public function run(): void {
        $this->call([
            AdminUserSeeder::class,
            MonthlyDiagnosesSeeder::class,
            MonthlyDrugUsageSeeder::class,
            DistrictSeeder::class,
            HealthFacilitySeeder::class,
            AreaMetricSeeder::class,
        ]);
    }
}
