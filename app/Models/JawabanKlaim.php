<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JawabanKlaim extends Model
{
    protected $fillable = [
        'klaim_id',
        'pertanyaan_verifikasi_id',
        'jawaban',
    ];

    public function klaim()
    {
        return $this->belongsTo(Klaim::class);
    }

    public function pertanyaanVerifikasi()
    {
        return $this->belongsTo(PertanyaanVerifikasi::class);
    }
}