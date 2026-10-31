<?php

namespace App\Http\Controllers;

use App\Models\LaporanKehilangan;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MyReportController extends Controller
{
    public function index(Request $request)
    {
        // Tandai semua notifikasi menjadi "Sudah Dibaca"
        $request->user()->notifications()->where('is_read', false)->update(['is_read' => true]);

        $myReports = \App\Models\LaporanKehilangan::with(['kategori', 'klaim.laporanPenemuan.user', 'matches'])
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return Inertia::render('User/BarangHilangku', [
            'myReports' => $myReports
        ]);
    }
}