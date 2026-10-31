<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanPenemuan extends Model
{
    protected $table = 'laporan_penemuans';

    protected $fillable = [
        'user_id',
        'kategori_id',
        'nama_barang',
        'deskripsi',
        'foto',
        'lokasi_penyimpanan',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function pertanyaanVerifikasi()
    {
        return $this->hasMany(PertanyaanVerifikasi::class);
    }
}