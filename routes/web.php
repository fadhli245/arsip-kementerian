<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\ArsipController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController; // <-- Pastikan ini ada
use App\Http\Controllers\Pegawai\ArsipController as PegawaiArsipController;

/*
|--------------------------------------------------------------------------
| 1. RUTE OTENTIKASI (LOGIN & LOGOUT)
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    if (Auth::check()) {
        if (Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('pegawai.dashboard');
    }
    return view('auth.login');
})->name('login');

Route::post('/login-proses', [LoginController::class, 'authenticate'])->name('login.proses');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


/*
|--------------------------------------------------------------------------
| 2. RUTE KHUSUS ADMIN (Dashboard, Kelola Arsip, & Manajemen Pengguna)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->group(function () {
    
    // Dashboard Admin
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // Manajemen Arsip (CRUD)
    Route::get('/admin/arsip', [ArsipController::class, 'index'])->name('admin.arsip.index');
    Route::get('/admin/arsip/create', [ArsipController::class, 'create'])->name('admin.arsip.create');
    Route::post('/admin/arsip', [ArsipController::class, 'store'])->name('admin.arsip.store');
    Route::get('/admin/arsip/{id}/download', [ArsipController::class, 'download'])->name('admin.arsip.download');
    Route::get('/admin/arsip/{id}/edit', [ArsipController::class, 'edit'])->name('admin.arsip.edit');
    Route::put('/admin/arsip/{id}', [ArsipController::class, 'update'])->name('admin.arsip.update');
    Route::delete('/admin/arsip/{id}', [ArsipController::class, 'destroy'])->name('admin.arsip.destroy');

    // Manajemen Pengguna (Staf/Admin) -> PINDAH KE SINI AGAR BISA DIAKSES ADMIN
    Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('/admin/users/create', [UserController::class, 'create'])->name('admin.users.create');
    Route::post('/admin/users', [UserController::class, 'store'])->name('admin.users.store');
    Route::delete('/admin/users/{id}', [UserController::class, 'destroy'])->name('admin.users.destroy');

});


/*
|--------------------------------------------------------------------------
| 3. RUTE KHUSUS PEGAWAI / USER
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:user,pegawai'])->group(function () {
    
    // Dashboard Pegawai
    Route::get('/pegawai/dashboard', function () { 
        return view('pegawai.dashboard'); 
    })->name('pegawai.dashboard');

    // Pencarian & Unduh Arsip
    Route::get('/pegawai/arsip', [PegawaiArsipController::class, 'index'])->name('pegawai.arsip.index');
    Route::get('/pegawai/arsip/{id}/download', [PegawaiArsipController::class, 'download'])->name('pegawai.arsip.download');

});