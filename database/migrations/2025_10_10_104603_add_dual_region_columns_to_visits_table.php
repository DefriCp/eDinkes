<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('visits', function (Blueprint $table) {
            $table->string('facility_kecamatan_id')->nullable()->index();
            $table->string('facility_kecamatan_nama')->nullable();
            $table->string('facility_desa_id')->nullable()->index();
            $table->string('facility_desa_nama')->nullable();
            $table->string('patient_kecamatan_id')->nullable()->index();
            $table->string('patient_kecamatan_nama')->nullable();
            $table->string('patient_desa_id')->nullable()->index();
            $table->string('patient_desa_nama')->nullable();
        });
    }

    public function down(): void {
        Schema::table('visits', function (Blueprint $table) {
            $table->dropColumn([
              'facility_kecamatan_id','facility_kecamatan_nama',
              'facility_desa_id','facility_desa_nama',
              'patient_kecamatan_id','patient_kecamatan_nama',
              'patient_desa_id','patient_desa_nama',
            ]);
        });
    }
};
