<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\Arsip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArsipController extends Controller
{
    // Menampilkan halaman pencarian & daftar arsip untuk pegawai
    public function index(Request $request)
    {
        $query = Arsip::query();

        // Fitur Pencarian berdasarkan Kata Kunci (Judul atau Nomor Surat)
        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->where('judul', 'like', '%' . $request->search . '%')
                  ->orWhere('nomor_surat', 'like', '%' . $request->search . '%'); // Diperbaiki dari nomor_dokumen ke nomor_surat
            });
        }

        // Fitur Filter berdasarkan Kategori
        if ($request->has('kategori') && $request->kategori != '') {
            $query->where('kategori', $request->kategori);
        }

        // Ambil data terbaru
        $arsips = $query->latest()->get();

        return view('pegawai.arsip.index', compact('arsips'));
    }

    // Fungsi bagi Pegawai untuk mengunduh PDF (Versi Direct Path)
    public function download($id)
    {
        $arsip = Arsip::findOrFail($id);
        
        // Bersihkan path jika ada awalan slash atau direktori ganda
        $path = str_replace('public/', '', $arsip->file_path);

        // Path absolut pertama: langsung di storage/app/public/
        $file1 = storage_path('app/public/' . $path);
        
        // Path absolut kedua: langsung di folder public/storage/ (hasil link)
        $file2 = public_path('storage/' . $path);

        if (file_exists($file1)) {
            return response()->download($file1);
        }

        if (file_exists($file2)) {
            return response()->download($file2);
        }

        // Jika benar-benar tidak ketemu di manapun
        return back()->with('error', 'File fisik tidak ditemukan. Pastikan file sudah di-upload dengan benar.');
    }
}