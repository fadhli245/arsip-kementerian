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

        // Fitur Pencarian berdasarkan Kata Kunci (Judul atau Nomor Dokumen)
        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->where('judul', 'like', '%' . $request->search . '%')
                  ->orWhere('nomor_dokumen', 'like', '%' . $request->search . '%');
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

    // Fungsi bagi Pegawai untuk mengunduh PDF
    public function download($id)
    {
        $arsip = Arsip::findOrFail($id);

        if ($arsip->file_path && Storage::disk('public')->exists($arsip->file_path)) {
            return Storage::disk('public')->download($arsip->file_path);
        }

        return back()->with('error', 'Maaf, file dokumen tidak ditemukan di server.');
    }
}