<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('posyandus', function (Blueprint $t) {
            $t->id();
            $t->string('id_posyandu')->nullable()->index();  
            $t->string('provinsi')->nullable();
            $t->string('kabupaten')->nullable();
            $t->string('kecamatan')->nullable();
            $t->string('kode_desa')->nullable();
            $t->string('desa')->nullable();

            $t->string('noregistrasi')->nullable();
            $t->string('nama_puskesmas')->nullable();
            $t->string('nama_posyandu')->nullable();

            $t->string('kriteria_1')->nullable();
            $t->string('kriteria_2')->nullable();
            $t->string('kriteria_3')->nullable();
            $t->string('status_posyandu_aktif')->nullable();

            $t->string('kriteria_siklus_hidup_1')->nullable();
            $t->string('kriteria_siklus_hidup_2')->nullable();
            $t->string('kriteria_siklus_hidup_3')->nullable();
            $t->string('status_posyandu_siklus_hidup')->nullable();

            $t->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('posyandus'); }
};
