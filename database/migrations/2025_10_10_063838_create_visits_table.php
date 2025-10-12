<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('visits', function (Blueprint $table) {
            $table->id();
            $table->enum('facility_type', ['puskesmas','posyandu']);
            $table->unsignedBigInteger('facility_id'); 
            $table->date('tanggal');
            $table->string('nama_pasien');
            $table->string('no_erm')->nullable();
            $table->string('nik', 40)->nullable();
            $table->string('no_rm_lama')->nullable();
            $table->string('no_dokumen_rm')->nullable();
            $table->enum('jenis_kelamin', ['L','P'])->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->unsignedSmallInteger('umur')->nullable();
            $table->string('pekerjaan')->nullable();
            $table->string('alamat')->nullable();
            $table->string('agama')->nullable();
            $table->string('status_pernikahan')->nullable();
            $table->string('kecamatan_id')->nullable();
            $table->string('kecamatan_nama')->nullable();
            $table->string('desa_id')->nullable();    
            $table->string('desa_nama')->nullable();
            $table->string('nama_ayah')->nullable();
            $table->string('jenis_kunjungan')->nullable(); 
            $table->string('kunjungan')->nullable();   
            $table->string('poli')->nullable();          
            $table->string('asuransi')->nullable();
            $table->string('no_asuransi')->nullable();
            $table->string('diagnosa')->nullable();
            $table->string('jenis_kasus')->nullable();
            $table->timestamps();
            $table->index(['facility_type','facility_id']);
            $table->index(['tanggal']);
            $table->index(['nik']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('visits');
    }
};
