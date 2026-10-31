<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jawaban_klaims', function (Blueprint $table) {
            $table->id();
            $table->foreignId('klaim_id')->constrained('klaims')->cascadeOnDelete();
            $table->foreignId('pertanyaan_verifikasi_id')->constrained('pertanyaan_verifikasis')->cascadeOnDelete();
            $table->text('jawaban');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jawaban_klaims');
    }
};