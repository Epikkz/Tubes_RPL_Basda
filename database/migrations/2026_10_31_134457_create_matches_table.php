<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laporan_kehilangan_id')->constrained('laporan_kehilangans')->cascadeOnDelete();
            $table->foreignId('laporan_penemuan_id')->constrained('laporan_penemuans')->cascadeOnDelete();
            $table->integer('skor'); // Skor kecocokan sistem[cite: 8]
            $table->enum('status', ['disarankan', 'diabaikan'])->default('disarankan'); // Opsi admin untuk mengabaikan[cite: 8]
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matches');
    }
};
