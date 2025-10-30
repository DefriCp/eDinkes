<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('visits', function (Blueprint $table) {
            // Ubah enum jadi string (lebih fleksibel)
            try { $table->string('facility_type', 30)->change(); } catch (\Throwable $e) {}
            if (!Schema::hasColumn('visits','kode_diagnosa')) {
                $table->string('kode_diagnosa')->nullable()->after('no_asuransi');
                $table->index('kode_diagnosa');
            }
        });
    }
    public function down(): void {
        Schema::table('visits', function (Blueprint $table) {
            if (Schema::hasColumn('visits','kode_diagnosa')) {
                $table->dropIndex(['kode_diagnosa']);
                $table->dropColumn('kode_diagnosa');
            }
        });
    }
};
