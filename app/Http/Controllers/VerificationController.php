<?php

namespace App\Http\Controllers;

use App\Models\Klaim;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class VerificationController extends Controller
{
    // Menampilkan daftar klaim yang masuk ke penemu
    public function index(Request $request)
    {
        $klaimMasuk = Klaim::with([
            'laporanKehilangan.user',
            'laporanPenemuan',
            'jawabanKlaim.pertanyaanVerifikasi'
        ])
        ->whereHas('laporanPenemuan', function ($query) use ($request) {
            $query->where('user_id', $request->user()->id);
        })
        ->latest()
        ->get();

        return Inertia::render('User/VerifikasiKlaim', [
            'klaimMasuk' => $klaimMasuk
        ]);
    }

    // Aksi Approve / Reject oleh Penemu
    public function respond(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:disetujui,ditolak'
        ]);

        $klaim = Klaim::with(['laporanKehilangan.user', 'laporanPenemuan'])->findOrFail($id);
        $klaim->status = $validated['status'];
        $klaim->save();

        $pemilik = $klaim->laporanKehilangan->user;
        $penemu = $request->user();

        if ($validated['status'] === 'disetujui') {
            // Beri +50 EXP ke Penemu
            $penemu->bounty_xp = ($penemu->bounty_xp ?? 0) + 50;
            if ($penemu->bounty_xp >= 100) {
                $penemu->bounty_level = ($penemu->bounty_level ?? 1) + 1;
                $penemu->bounty_xp -= 100;
            }
            $penemu->save();

            Notification::create([
                'user_id' => $pemilik->id,
                'title' => 'Klaim Disetujui! 🎉',
                'message' => 'Jawaban verifikasimu untuk barang ' . $klaim->laporanPenemuan->nama_barang . ' disetujui! Hubungi WhatsApp Penemu: ' . ($penemu->no_hp ?? 'No HP belum diisi'),
            ]);
        } else {
            Notification::create([
                'user_id' => $pemilik->id,
                'title' => 'Klaim Ditolak ❌',
                'message' => 'Jawaban verifikasimu untuk barang ' . $klaim->laporanPenemuan->nama_barang . ' kurang tepat.',
            ]);
        }

        return redirect()->back()->with('message', 'Respon klaim berhasil disimpan!');
    }
}