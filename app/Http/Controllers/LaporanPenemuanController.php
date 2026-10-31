<?php

namespace App\Http\Controllers;

use App\Models\LaporanPenemuan;
use App\Models\LaporanKehilangan;
use App\Models\MatchResult;
use Illuminate\Http\Request;

class LaporanPenemuanController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kategori_id' => 'required|exists:kategoris,id',
            'nama_barang' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'pertanyaan' => 'required|array|min:1',
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('laporan_penemuan', 'public');
        }

        $penemuan = $request->user()->laporanPenemuan()->create([
            'kategori_id' => $validated['kategori_id'],
            'nama_barang' => $validated['nama_barang'],
            'deskripsi' => $validated['deskripsi'],
            'foto' => $validated['foto'] ?? null,
            'status' => 'menunggu', // Akan divalidasi admin nanti
        ]);

        foreach ($validated['pertanyaan'] as $item) {
            $penemuan->pertanyaanVerifikasi()->create(['pertanyaan' => $item]);
        }

        // TRIGGER AI MATRIX SETELAH PENEMUAN DIBUAT
        $this->hitungMatrixTemuan($penemuan);

        return redirect()->back()->with('message', 'Laporan Temuan berhasil dibuat! Cek AI Matching Matrix.');
    }

    // TAMBAHKAN FUNGSI INI
    private function hitungMatrixTemuan(LaporanPenemuan $temuan)
    {
        $kehilanganList = LaporanKehilangan::where('status', 'hilang')->get();

        foreach ($kehilanganList as $kehilangan) {
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