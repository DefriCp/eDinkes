<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('monthly_diagnoses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code', 10);
            $table->string('name');
            $table->unsignedTinyInteger('month');
            $table->unsignedSmallInteger('year');
            $table->unsignedInteger('total_cases')->default(0);
            $table->timestamps();
            $table->index(['year','month']);
            $table->index('code');
        });
    }
    public function down(): void { Schema::dropIfExists('monthly_diagnoses'); }
};
