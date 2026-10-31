<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Kategori;
use App\Models\LaporanKehilangan;
use App\Models\LaporanPenemuan;
use App\Models\MatchResult;
use Illuminate\Database\Seeder;

class DemoMatchSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first() ?? User::factory()->create([
            'name' => 'Donga Detektif',
            'email' => 'donga@example.com',
            'password' => bcrypt('password'),
            'role' => 'user'
        ]);

        $kategori = Kategori::first() ?? Kategori::create(['nama_kategori' => 'Elektronik']);

        // 1. Barang Hilang (Milik User)
        $kehilangan = LaporanKehilangan::create([
            'user_id' => $user->id,
            'kategori_id' => $kategori->id,
            'nama_barang' => 'Laptop Asus ROG Hitam',
            'deskripsi' => 'Laptop gaming dengan stiker sticker anime di bodi belakang',
            'tanggal_hilang' => now(),
            'status' => 'hilang'
        ]);

        // 2. Barang Penemuan (Ditemukan Orang Lain)
        $penemuan = LaporanPenemuan::create([
            'user_id' => $user->id,
            'kategori_id' => $kategori->id,
            'nama_barang' => 'Laptop Asus ROG Stiker',
            'deskripsi' => 'Ditemukan di kantin lantai 2, casing banyak stiker',
            'lokasi_penyimpanan' => 'Pos Satpam Utama',
            'status' => 'tersedia'
        ]);

        // Tambah Pertanyaan Verifikasi dari Penemu
        $penemuan->pertanyaanVerifikasi()->createMany([
            ['pertanyaan' => 'Sebutkan merk stiker utama di pojok kanan laptop?'],
            ['pertanyaan' => 'Berapa ukuran adapter charger-nya?']
        ]);

        // 3. Hasil Matching Matrix AI (Dummy Skor 95%)
        MatchResult::create([
            'laporan_kehilangan_id' => $kehilangan->id,
            'laporan_penemuan_id' => $penemuan->id,
            'skor' => 95,
            'status' => 'disarankan'
        ]);
    }
}