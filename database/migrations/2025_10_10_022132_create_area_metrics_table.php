<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('area_metrics', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('district_id');
            $table->unsignedTinyInteger('month');
            $table->unsignedSmallInteger('year');
            $table->decimal('idl_pct', 6, 2)->nullable();
            $table->decimal('k1_pct', 6, 2)->nullable();
            $table->decimal('k4_pct', 6, 2)->nullable();
            $table->unsignedInteger('dbd_cases')->default(0);
            $table->unsignedInteger('visits')->default(0);
            $table->timestamps();

            $table->foreign('district_id')->references('id')->on('districts')->cascadeOnDelete();
            $table->unique(['district_id','month','year']);
            $table->index(['year','month']);
        });
    }
    public function down(): void { Schema::dropIfExists('area_metrics'); }
};
