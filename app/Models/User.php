<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'bounty_level', 'bounty_xp', 'is_active'
    ];

    // TAMBAHKAN BARIS INI: Otomatis set role 'user' untuk pendaftar baru
    protected $attributes = [
        'role' => 'user',
    ];

    public function laporanKehilangan() { return $this->hasMany(LaporanKehilangan::class); }
    public function laporanPenemuan() { return $this->hasMany(LaporanPenemuan::class); }
    public function notifications() { return $this->hasMany(Notification::class); }
}