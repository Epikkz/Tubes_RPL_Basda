<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanKehilangan extends Model
{
    protected $table = 'laporan_kehilangans';

    protected $fillable = [
        'user_id',
        'kategori_id',
        'nama_barang',
        'deskripsi',
        'tanggal_hilang',
        'foto',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    // TAMBAHKAN RELASI INI
    public function klaim()
    {
        return $this->hasMany(Klaim::class);
    }

    public function matches()
    {
        return $this->hasMany(MatchResult::class, 'laporan_kehilangan_id');
    }
}