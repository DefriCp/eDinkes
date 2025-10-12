<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('health_facilities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('district_id')->nullable();
            $table->string('name');
            $table->string('type')->default('Puskesmas');
            $table->string('address')->nullable();
            $table->decimal('lat', 10, 6)->nullable();
            $table->decimal('lng', 10, 6)->nullable();
            $table->timestamps();

            $table->foreign('district_id')->references('id')->on('districts')->nullOnDelete();
            $table->index(['lat','lng']);
        });
    }
    public function down(): void { Schema::dropIfExists('health_facilities'); }
};
