<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Arsip;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalArsip       = Arsip::count();
        $totalSuratMasuk  = Arsip::where('kategori', 'Surat Masuk')->count();
        $totalSuratKeluar = Arsip::where('kategori', 'Surat Keluar')->count();
        $totalSK          = Arsip::where('kategori', 'Surat Keputusan (SK)')->count();
        $totalLaporan     = Arsip::where('kategori', 'Laporan Kegiatan')->count();
        $totalPegawai     = User::where('role', 'user')->count();

        $arsipTerbaru = Arsip::latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalArsip',
            'totalSuratMasuk',
            'totalSuratKeluar',
            'totalSK',
            'totalLaporan',
            'totalPegawai',
            'arsipTerbaru'
        ));
    }
}