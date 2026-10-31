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
        Schema::create('pertanyaan_verifikasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laporan_penemuan_id')->constrained('laporan_penemuans')->cascadeOnDelete();
            $table->text('pertanyaan'); // Pertanyaan yang disusun oleh penemu
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pertanyaan_verifikasis');
    }
};
