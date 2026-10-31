<?php

namespace App\Http\Controllers;

use App\Models\MatchResult;
use App\Models\Klaim;
use App\Models\JawabanKlaim;
use App\Models\Notification;
use App\Models\LaporanPenemuan;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MatchingController extends Controller
{
    public function index(Request $request)
    {
        $matches = MatchResult::with(['laporanKehilangan', 'laporanPenemuan.pertanyaanVerifikasi'])
            ->whereHas('laporanKehilangan', function ($query) use ($request) {
                $query->where('user_id', $request->user()->id);
            })
            ->where('status', 'disarankan')
            ->orderBy('skor', 'desc')
            ->get();

        return Inertia::render('User/MatchingMatrix', [
            'matches' => $matches
        ]);
    }

    public function submitKlaim(Request $request)
    {
        $validated = $request->validate([
            'laporan_kehilangan_id' => 'required|exists:laporan_kehilangans,id',
            'laporan_penemuan_id' => 'required|exists:laporan_penemuans,id',
            'jawaban' => 'required|array',
        ]);

        // 1. Buat Record Klaim
        $klaim = Klaim::create([
            'laporan_kehilangan_id' => $validated['laporan_kehilangan_id'],
            'laporan_penemuan_id' => $validated['laporan_penemuan_id'],
            'user_id' => $request->user()->id,
            'skor_verifikasi' => 80,
            'status' => 'dalam_klaim'
        ]);

        // 2. Simpan Setiap Jawaban Verifikasi Pemilik
        foreach ($validated['jawaban'] as $pertanyaanId => $jawabanTeks) {
            JawabanKlaim::create([
                'klaim_id' => $klaim->id,
                'pertanyaan_verifikasi_id' => $pertanyaanId,
                'jawaban' => $jawabanTeks,
            ]);
        }

        // 3. Tambah +50 XP ke Penemu & Naikkan Level Jika XP >= 100
        $penemuan = LaporanPenemuan::find($validated['laporan_penemuan_id']);
        $penemu = User::find($penemuan->user_id);

        if ($penemu) {
            $penemu->bounty_xp = ($penemu->bounty_xp ?? 0) + 50;

            if ($penemu->bounty_xp >= 100) {
                $penemu->bounty_level = ($penemu->bounty_level ?? 1) + 1;
                $penemu->bounty_xp = $penemu->bounty_xp - 100;
            }

            $penemu->save();

            // 4. Kirim Notifikasi
            Notification::create([
                'user_id' => $penemu->id,
                'title' => 'Klaim Masuk! 🕵️',
                'message' => 'Seseorang mengklaim barang temuanmu: ' . $penemuan->nama_barang . '. Kamu mendapatkan +50 XP!',
            ]);
        }

        return redirect()->back()->with('message', 'Klaim berhasil dikirim! Menunggu verifikasi admin.');
    }
}