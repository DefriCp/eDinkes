<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('monthly_drug_usage', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('drug_name');
            $table->unsignedTinyInteger('month');
            $table->unsignedSmallInteger('year');
            $table->unsignedInteger('total_usage')->default(0);
            $table->timestamps();
            $table->index(['year','month']);
            $table->index('drug_name');
        });
    }
    public function down(): void { Schema::dropIfExists('monthly_drug_usage'); }
};
