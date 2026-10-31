<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        Kategori::create(['nama_kategori' => 'Elektronik']);
        Kategori::create(['nama_kategori' => 'Dokumen']);
        Kategori::create(['nama_kategori' => 'Pakaian/Aksesori']);
        Kategori::create(['nama_kategori' => 'Kunci']);
    }
}