<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PertanyaanVerifikasi extends Model
{
    protected $table = 'pertanyaan_verifikasis';
    protected $fillable = ['laporan_penemuan_id', 'pertanyaan'];

    public function laporanPenemuan()
    {
        return $this->belongsTo(LaporanPenemuan::class);
    }
}