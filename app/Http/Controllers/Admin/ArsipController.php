<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Arsip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Tambahkan ini untuk kelola file
use Illuminate\Support\Facades\Auth;

class ArsipController extends Controller
{
    public function index()
    {
        $arsips = Arsip::latest()->get();
        return view('admin.arsip.index', compact('arsips'));
    }

    public function create()
    {
        return view('admin.arsip.create');
    }

    public function store(Request $request)
{
    // 1. Validasi input sesuai form Blade
    $request->validate([
        'file_arsip'       => 'required|mimes:pdf|max:5120', // Hanya PDF, Maks 5MB
        'judul'            => 'required|string|max:255',
        'kategori'         => 'required|string',
        'tanggal_dokumen'  => 'required|date',
        'keterangan'       => 'nullable|string',
    ]);

    // 2. Simpan file PDF ke folder storage/app/public/dokumen_arsip
    $filePath = $request->file('file_arsip')->store('dokumen_arsip', 'public');

    // 3. Simpan record ke database
    Arsip::create([
        'judul'           => $request->judul,
        'kategori'        => $request->kategori,
        'tanggal_dokumen' => $request->tanggal_dokumen,
        'keterangan'      => $request->keterangan,
        'file_path'       => $filePath,
        'user_id'         => Auth::id(),
    ]);

    return redirect()->route('admin.arsip.index')->with('success', 'Arsip berhasil disimpan!');
}

    // 4. Fungsi khusus untuk mengunduh file
    public function download($id)
    {
        $arsip = Arsip::findOrFail($id);
        
        // Cek apakah filenya benar-benar ada di server
        if ($arsip->file_path && Storage::disk('public')->exists($arsip->file_path)) {
            return Storage::disk('public')->download($arsip->file_path);
        }
        
        return back()->with('error', 'Maaf, file fisik dokumen tidak ditemukan di server.');
    }

   // Menampilkan form edit
    public function edit($id)
    {
        $arsip = Arsip::findOrFail($id);
        return view('admin.arsip.edit', compact('arsip'));
    }

    // Memproses pembaruan data
    public function update(Request $request, $id)
    {
        $request->validate([
            'judul'            => 'required|string|max:255',
            'kategori'         => 'required|string',
            'tanggal_dokumen'  => 'required|date',
            'keterangan'       => 'nullable|string',
            'file_arsip'       => 'nullable|mimes:pdf|max:5120', // "nullable" karena tidak wajib ganti file
        ]);

        $arsip = Arsip::findOrFail($id);

        // Jika Admin mengunggah file baru
        if ($request->hasFile('file_arsip')) {
            // Hapus file lama dari folder storage
            if (Storage::disk('public')->exists($arsip->file_path)) {
                Storage::disk('public')->delete($arsip->file_path);
            }
            // Simpan file baru
            $filePath = $request->file('file_arsip')->store('dokumen_arsip', 'public');
            $arsip->file_path = $filePath;
        }

        // Perbarui teks data lainnya
        $arsip->judul = $request->judul;
        $arsip->kategori = $request->kategori;
        $arsip->tanggal_dokumen = $request->tanggal_dokumen;
        $arsip->keterangan = $request->keterangan;
        $arsip->save();

        return redirect()->route('admin.arsip.index')->with('success', 'Data Arsip berhasil diperbarui!');
    }

    // Menghapus arsip
    public function destroy($id)
    {
        $arsip = Arsip::findOrFail($id);

        // Hapus file fisiknya dulu dari folder
        if (Storage::disk('public')->exists($arsip->file_path)) {
            Storage::disk('public')->delete($arsip->file_path);
        }

        // Baru hapus datanya dari database
        $arsip->delete();

        return redirect()->route('admin.arsip.index')->with('success', 'Arsip dan file PDF berhasil dihapus permanen!');
    }
    
}