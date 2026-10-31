<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Klaim extends Model
{
    protected $table = 'klaims';

    protected $fillable = [
        'laporan_kehilangan_id',
        'laporan_penemuan_id',
        'user_id',
        'skor_verifikasi',
        'status',
    ];

    public function laporanKehilangan()
    {
        return $this->belongsTo(LaporanKehilangan::class);
    }

    public function laporanPenemuan()
    {
        return $this->belongsTo(LaporanPenemuan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // TAMBAHKAN RELASI INI
    public function jawabanKlaim()
    {
        return $this->hasMany(JawabanKlaim::class);
    }
}