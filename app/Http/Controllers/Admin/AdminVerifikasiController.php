<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LaporanPenemuan;
use App\Models\Klaim;
use Illuminate\Http\Request;

class AdminVerifikasiController extends Controller
{
    // Verifikasi Penemuan oleh Admin[cite: 5]
    public function verifikasiPenemuan(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:tersedia,ditolak',
            'lokasi_penyimpanan' => 'required_if:status,tersedia', // Lokasi wajib diisi jika valid[cite: 5]
        ]);

        $penemuan = LaporanPenemuan::findOrFail($id);

        if ($request->status === 'tersedia') {
            $penemuan->update([
                'status' => 'tersedia', // Mengubah laporan menjadi tersedia[cite: 5]
                'lokasi_penyimpanan' => $request->lokasi_penyimpanan // Mengisi lokasi penyimpanan[cite: 5]
            ]);
        } else {
            $penemuan->update(['status' => 'ditolak']); // Mengubah status menjadi ditolak[cite: 5]
        }

        return redirect()->back()->with('message', 'Status penemuan berhasil diisi.');
    }

    // Verifikasi Klaim oleh Admin[cite: 6]
    public function verifikasiKlaim(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:disetujui,ditolak',
            'catatan_penolakan' => 'required_if:status,ditolak', // Mengisi catatan penolakan jika ditolak[cite: 6]
        ]);

        $klaim = Klaim::findOrFail($id);

        if ($request->status === 'disetujui') {
            $klaim->update(['status' => 'disetujui']); // Mengubah status klaim menjadi disetujui[cite: 6]
            $klaim->laporanKehilangan->update(['status' => 'selesai']); // Laporan Hilang Selesai[cite: 6]
            $klaim->laporanPenemuan->update(['status' => 'ditolak']); // Mengubah status temuan agar tidak dapat diklaim lagi
        } else {
            $klaim->update([
                'status' => 'ditolak',
                'catatan_penolakan' => $request->catatan_penolakan // Mengisi Catatan Penolakan[cite: 6]
            ]);
        }

        return redirect()->back();
    }
}