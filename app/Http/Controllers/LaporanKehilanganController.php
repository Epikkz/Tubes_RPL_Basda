<?php

namespace App\Http\Controllers;

use App\Models\LaporanKehilangan;
use App\Models\LaporanPenemuan;
use App\Models\MatchResult;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LaporanKehilanganController extends Controller
{
 public function index(Request $request)
    {
        if (strtolower(trim($request->user()->role)) === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        $reports = \App\Models\LaporanKehilangan::with(['kategori', 'user'])
            ->where('status', 'hilang')
            ->latest()
            ->get();

        $kategoris = \App\Models\Kategori::all();
        
        // Hitung notifikasi belum terbaca untuk memunculkan badge merah
        $unreadCount =$request->user()->notifications()->where('is_read', false)->count();

        return Inertia::render('Dashboard', [
            'reports' => $reports,
            'kategoris' => $kategoris,
            'unreadCount' => $unreadCount
        ]);
    }

    // TAMBAHKAN FUNGSI HAPUS INI
    public function destroy(Request $request, $id)
        {
            $laporan = \App\Models\LaporanKehilangan::where('id', $id)
                ->where('user_id', $request->user()->id)
                ->firstOrFail();
                
            $laporan->delete();

            // Tambahkan angka 303 di dalam fungsi back()
            return back(303)->with('message', 'Laporan barang hilang berhasil dihapus!');
        }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kategori_id' => 'required|exists:kategoris,id',
            'nama_barang' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tanggal_hilang' => 'required|date',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('laporan_kehilangan', 'public');
        }

        $laporan = $request->user()->laporanKehilangan()->create($validated);

        // Algoritma Matching Matriks otomatis
        $this->hitungMatrix($laporan);

        return redirect()->back()->with('message', 'Laporan barang hilang berhasil dibuat!');
    }

    // GANTI FUNGSI INI DI LaporanKehilanganController.php
    private function hitungMatrix(LaporanKehilangan $kehilangan)
    {
        // Ambil SEMUA barang temuan, jangan hanya yang 'tersedia'
        $temuanList = LaporanPenemuan::all();

        foreach ($temuanList as $temuan) {
            $skor = 0;

            if ($kehilangan->kategori_id == $temuan->kategori_id) {
                $skor += 40;
            }

            $namaKehilangan = strtolower(trim($kehilangan->nama_barang));
            $namaTemuan = strtolower(trim($temuan->nama_barang));

            if (similar_text($namaKehilangan, $namaTemuan, $percent)) {
                $skor += round(($percent / 100) * 60);
            }

            if ($skor >= 50) {
                MatchResult::updateOrCreate(
                    ['laporan_kehilangan_id' => $kehilangan->id, 'laporan_penemuan_id' => $temuan->id],
                    ['skor' => $skor, 'status' => 'disarankan']
                );
            }
        }
    }
}