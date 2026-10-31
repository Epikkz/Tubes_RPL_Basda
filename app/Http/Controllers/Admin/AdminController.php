<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Kategori;
use App\Models\LaporanPenemuan;
use App\Models\Klaim;
use App\Models\MatchResult;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_temuan_menunggu' => LaporanPenemuan::where('status', 'menunggu')->count(),
            'total_klaim_baru' => Klaim::where('status', 'dalam_klaim')->count(),
        ];
        return Inertia::render('Admin/Dashboard', ['stats' => $stats]);
    }

    public function masterData()
    {
        return Inertia::render('Admin/MasterData', [
            'users' => User::latest()->get(),
            'kategoris' => Kategori::latest()->get()
        ]);
    }

    public function verifikasiPenemuan()
    {
        return Inertia::render('Admin/VerifikasiPenemuan', [
            'penemuans' => LaporanPenemuan::with(['user', 'kategori'])
                ->where('status', 'menunggu')
                ->latest()
                ->get()
        ]);
    }

    public function verifikasiKlaim()
    {
        return Inertia::render('Admin/VerifikasiKlaim', [
            'klaims' => Klaim::with(['laporanKehilangan.user', 'laporanPenemuan.user', 'user'])
                ->latest()
                ->get()
        ]);
    }

    public function pencocokan()
    {
        return Inertia::render('Admin/Pencocokan', [
            'matches' => MatchResult::with(['laporanKehilangan.user', 'laporanPenemuan.user'])
                ->latest()
                ->get()
        ]);
    }
}