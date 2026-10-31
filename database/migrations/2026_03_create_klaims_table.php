<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('klaims', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laporan_kehilangan_id')->constrained('laporan_kehilangans')->cascadeOnDelete();
            $table->foreignId('laporan_penemuan_id')->constrained('laporan_penemuans')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->integer('skor_verifikasi')->default(0);
            $table->text('catatan_penolakan')->nullable();
            $table->enum('status', ['dalam_klaim', 'disetujui', 'ditolak'])->default('dalam_klaim');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('klaims');
    }
};