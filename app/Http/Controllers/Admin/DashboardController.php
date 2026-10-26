<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Arsip;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Menghitung total statistik
        $totalArsip = Arsip::count();
        $suratMasuk = Arsip::where('kategori', 'Surat Masuk')->count();
        $suratKeluar = Arsip::where('kategori', 'Surat Keluar')->count();
        $sk = Arsip::where('kategori', 'Surat Keputusan (SK)')->count();
        $laporan = Arsip::where('kategori', 'Laporan Kegiatan')->count();

        // Mengambil 5 arsip terbaru yang baru saja diinput
        $arsipTerbaru = Arsip::orderBy('created_at', 'desc')->take(5)->get();

        return view('admin.dashboard', compact(
            'totalArsip', 
            'suratMasuk', 
            'suratKeluar', 
            'sk', 
            'laporan', 
            'arsipTerbaru'
        ));
    }

    public function aktivitas()
    {
        // Mengambil seluruh riwayat arsip yang diurutkan dari yang paling baru
        $riwayats = Arsip::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.aktivitas', compact('riwayats'));
    }
}