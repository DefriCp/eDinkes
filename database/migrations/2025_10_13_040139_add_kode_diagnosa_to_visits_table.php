<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('visits', function (Blueprint $table) {
            if (!Schema::hasColumn('visits','kode_diagnosa')) {
                $table->string('kode_diagnosa', 20)->nullable()->after('no_asuransi');
            }
        });
    }

    public function down(): void {
        Schema::table('visits', function (Blueprint $table) {
            if (Schema::hasColumn('visits','kode_diagnosa')) {
                $table->dropColumn('kode_diagnosa');
            }
        });
    }
};
