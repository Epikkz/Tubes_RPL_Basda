<?php

use App\Http\Controllers\LaporanKehilanganController;
use App\Http\Controllers\LaporanPenemuanController;
use App\Http\Controllers\MatchingController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\MyReportController;
use App\Http\Controllers\Admin\AdminController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () { return Inertia::render('Welcome'); });

// GROUP 1: HARUS LOGIN (BISA USER & ADMIN)
Route::middleware(['auth'])->group(function () {
    // DASHBOARD USER BIASA
    Route::get('/dashboard', [LaporanKehilanganController::class, 'index'])->name('dashboard');
    
    Route::post('/kehilangan', [LaporanKehilanganController::class, 'store'])->name('kehilangan.store');
    // PINDAHKAN KE SINI AGAR USER BISA HAPUS BARANGNYA SENDIRI
    Route::delete('/kehilangan/{id}', [LaporanKehilanganController::class, 'destroy'])->name('kehilangan.destroy');
    
    Route::post('/penemuan', [LaporanPenemuanController::class, 'store'])->name('penemuan.store');
    Route::get('/barang-hilangku', [MyReportController::class, 'index'])->name('barangku.index');
    Route::get('/matching', [MatchingController::class, 'index'])->name('matching.index');
    Route::post('/matching/klaim', [MatchingController::class, 'submitKlaim'])->name('matching.klaim');
    Route::get('/verifikasi-klaim', [VerificationController::class, 'index'])->name('verifikasi.index');
    Route::post('/verifikasi-klaim/{id}', [VerificationController::class, 'respond'])->name('verifikasi.respond');
});

// GROUP 2: KHUSUS ADMIN (SUPER DASHBOARD)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    Route::get('/master-data', [AdminController::class, 'masterData'])->name('master.data');
    Route::get('/verifikasi-penemuan', [AdminController::class, 'verifikasiPenemuan'])->name('penemuan');
    Route::get('/verifikasi-klaim', [AdminController::class, 'verifikasiKlaim'])->name('klaim');
    Route::get('/pencocokan', [AdminController::class, 'pencocokan'])->name('pencocokan');
});

require __DIR__.'/auth.php';