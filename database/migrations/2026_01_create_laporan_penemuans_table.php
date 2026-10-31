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
        Schema::create('laporan_penemuans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('kategori_id')->constrained('kategoris');
            $table->string('nama_barang');
            $table->text('deskripsi');
            $table->string('foto')->nullable();
            $table->string('lokasi_penyimpanan')->nullable(); // Diisi admin saat menyetujui[cite: 5]
            $table->enum('status', ['menunggu', 'tersedia', 'ditolak'])->default('menunggu'); // Status alur verifikasi admin[cite: 5]
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_penemuans');
    }
};
