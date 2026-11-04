<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\ArsipController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ArsipImportController;
use App\Http\Controllers\Admin\ArchiveCategoryController;
use App\Http\Controllers\Pegawai\ArsipController as PegawaiArsipController;
use App\Http\Controllers\Pegawai\DashboardController as PegawaiDashboardController;

/*
|--------------------------------------------------------------------------
| 1. RUTE OTENTIKASI (LOGIN & LOGOUT)
|--------------------------------------------------------------------------
*/
// Akar aplikasi mengarahkan pengguna ke dashboard atau form login.
Route::get('/', function () {
    if (Auth::check()) {
        if (in_array(Auth::user()->role, ['admin', 'super_admin', 'admin_divisi'], true)) {
            return redirect()->route('admin.dashboard');
        }
        if (Auth::user()->role === 'verifikator') {
            return redirect()->route('admin.permohonan.index');
        }
        return redirect()->route('pegawai.dashboard');
    }
    return view('auth.login');
});

// URL login eksplisit dipakai juga oleh middleware autentikasi Laravel.
Route::get('/login', function () {
    if (Auth::check()) {
        if (in_array(Auth::user()->role, ['admin', 'super_admin', 'admin_divisi'], true)) {
            return redirect()->route('admin.dashboard');
        }
        if (Auth::user()->role === 'verifikator') {
            return redirect()->route('admin.permohonan.index');
        }
        return redirect()->route('pegawai.dashboard');
    }
    return view('auth.login');
})->name('login');

// Form dan proses login menggunakan path yang sama dengan method berbeda.
Route::post('/login', [LoginController::class, 'authenticate'])->name('login.proses');
// Kompatibilitas untuk form login dari deployment lama.
Route::post('/login-proses', [LoginController::class, 'authenticate']);

// Rute logout menggunakan POST
Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');


/*
|--------------------------------------------------------------------------
| 2. RUTE KHUSUS ADMIN
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin,super_admin,admin_divisi'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard Admin
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Manajemen Arsip (CRUD & Fitur Tambahan)
    Route::get('/arsip/import', [ArsipImportController::class, 'index'])->name('arsip.import');
    Route::post('/arsip/import', [ArsipImportController::class, 'store'])->name('arsip.import.store'); 
    Route::get('/arsip/print', [ArsipController::class, 'print'])->name('arsip.print');
    Route::get('/arsip/export', [ArsipController::class, 'export'])->name('arsip.export');
    Route::get('/arsip/{id}/download', [ArsipController::class, 'download'])->name('arsip.download');
    
    // Resource CRUD Arsip Utama
    Route::resource('arsip', ArsipController::class);

    // Manajemen Pengguna (Super Admin dan Admin)
    Route::middleware('role:admin,super_admin')->group(function () {
        Route::patch('/users/{user}/status', [UserController::class, 'toggleActive'])->name('users.status');
        Route::resource('users', UserController::class)->except(['show']);
        Route::get('/kategori', [ArchiveCategoryController::class, 'index'])->name('kategori.index');
        Route::post('/kategori', [ArchiveCategoryController::class, 'store'])->name('kategori.store');
        Route::put('/kategori/{category}', [ArchiveCategoryController::class, 'update'])->name('kategori.update');
        Route::patch('/kategori/{category}/status', [ArchiveCategoryController::class, 'toggle'])->name('kategori.status');
    });

    // Riwayat Aktivitas
    Route::get('/aktivitas', [DashboardController::class, 'aktivitas'])->name('aktivitas');

});

Route::middleware(['auth', 'role:admin,super_admin,admin_divisi,verifikator'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/permohonan', [DashboardController::class, 'permohonan'])->name('permohonan.index');
    Route::patch('/permohonan/{archiveRequest}', [DashboardController::class, 'updatePermohonan'])->name('permohonan.update');
    Route::patch('/dokumen/{arsip}/approval', [DashboardController::class, 'updateArchiveApproval'])->name('arsip.approval');
});


/*
|--------------------------------------------------------------------------
| 3. RUTE KHUSUS PEGAWAI / USER
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:user,pegawai'])->prefix('pegawai')->name('pegawai.')->group(function () {
    
    // Dashboard Pegawai
    Route::get('/dashboard', [PegawaiDashboardController::class, 'index'])->name('dashboard');
    Route::post('/permohonan-arsip', [PegawaiDashboardController::class, 'storeRequest'])->name('permohonan.store');

    // Pencarian & Unduh Arsip Pegawai
    Route::get('/arsip', [PegawaiArsipController::class, 'index'])->name('arsip.index');
    Route::get('/arsip/{id}/download', [PegawaiArsipController::class, 'download'])->name('arsip.download');
    Route::get('/arsip/{id}/preview', [PegawaiArsipController::class, 'preview'])->name('arsip.preview');
});
