<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MatchResult extends Model
{
    protected $table = 'matches'; // Arahkan ke tabel matches
    protected $fillable = ['laporan_kehilangan_id', 'laporan_penemuan_id', 'skor', 'status'];

    public function laporanKehilangan()
    {
        return $this->belongsTo(LaporanKehilangan::class);
    }

    public function laporanPenemuan()
    {
        return $this->belongsTo(LaporanPenemuan::class);
    }
}