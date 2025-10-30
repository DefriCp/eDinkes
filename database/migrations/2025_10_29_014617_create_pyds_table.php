<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pyds', function (Blueprint $table) {
            $table->id();
            $table->string('provinsi')->nullable();
            $table->string('kabupaten')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('noregistrasi')->nullable();
            $table->string('nama_puskesmas')->nullable();
            $table->string('nama_posyandu')->nullable();
            $table->string('nama_kader')->nullable();
            $table->unsignedInteger('usia')->nullable();
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable();
            $table->string('nomor_sk')->nullable();
            $table->string('jenjang_pendidikan')->nullable();
            $table->text('alamat_ktp')->nullable();
            $table->text('alamat_domisili')->nullable();
            $table->string('kepemilikan_jkn')->nullable();
            $table->string('metode_koordinasi')->nullable();

            $table->string('status_pelatihan_posyandu')->nullable();
            $table->string('status_penilaian_posyandu')->nullable();
            $table->string('status_keterampilan_posyandu')->nullable();

            $table->string('status_pelatihan_bayi_balita')->nullable();
            $table->string('status_penilaian_bayi_balita')->nullable();
            $table->string('status_keterampilan_bayi_balita')->nullable();

            $table->string('status_pelatihan_ibu_hamil')->nullable();
            $table->string('status_penilaian_ibu_hamil')->nullable();
            $table->string('status_keterampilan_ibu_hamil')->nullable();

            $table->string('status_pelatihan_remaja')->nullable();
            $table->string('status_penilaian_remaja')->nullable();
            $table->string('status_keterampilan_remaja')->nullable();

            $table->string('status_pelatihan_lansia')->nullable();
            $table->string('status_penilaian_lansia')->nullable();
            $table->string('status_keterampilan_lansia')->nullable();

            $table->string('status_pelatihan_timbang_ukur')->nullable();
            $table->string('status_penilaian_timbang_ukur')->nullable();

            $table->string('tingkatan_kader')->nullable();
            $table->boolean('sudah_mengikuti_25_keterampilan_dasar')->default(false);
            $table->boolean('sudah_dinilai_keterampilan_dasar')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pyds');
    }
};
