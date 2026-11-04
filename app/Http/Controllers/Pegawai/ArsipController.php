<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\Arsip;
use App\Models\ArchiveCategory;
use App\Services\ActivityLogger;
use App\Services\ArchiveAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArsipController extends Controller
{
    // Menampilkan halaman pencarian & daftar arsip untuk pegawai
    public function index(Request $request)
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'kategori' => ['nullable', 'string', 'max:100'],
        ], [
            'search.string' => 'Kata kunci pencarian harus berupa teks.',
            'search.max' => 'Kata kunci pencarian maksimal 100 karakter.',
        ]);

        $query = ArchiveAccess::applyTo(Arsip::query(), $request->user());

        // Fitur Pencarian berdasarkan Kata Kunci (Judul atau Nomor Surat)
        if (!empty($validated['search'])) {
            $query->where(function($q) use ($validated) {
                $q->where('judul', 'like', '%' . $validated['search'] . '%')
                  ->orWhere('nomor_surat', 'like', '%' . $validated['search'] . '%'); // Diperbaiki dari nomor_dokumen ke nomor_surat
            });
        }

        // Fitur Filter berdasarkan Kategori
        if (!empty($validated['kategori'])) {
            $query->where('kategori', $validated['kategori']);
        }

        // Ambil data terbaru
        $arsips = $query->latest()->get();
        $kategoriList = ArchiveCategory::where('is_active', true)->orderBy('name')->pluck('name');

        return view('pegawai.arsip.index', compact('arsips', 'kategoriList'));
    }

    // Fungsi bagi Pegawai untuk mengunduh PDF (Versi Direct Path)
    public function download($id)
    {
        $arsip = ArchiveAccess::applyTo(Arsip::whereKey($id), request()->user())->firstOrFail();

        if (empty($arsip->file_path) || $arsip->file_path === '-') {
            return back()->with('error', 'Arsip ini belum memiliki file untuk diunduh.');
        }
        
        $path = $this->resolveFilePath($arsip->file_path ?? '');
        if ($path) {
            ActivityLogger::record('Unduh Arsip', 'Mengunduh arsip: ' . $arsip->judul, $arsip);
            return response()->download($path);
        }

        // Jika benar-benar tidak ketemu di manapun
        return back()->with('error', 'File fisik tidak ditemukan. Pastikan file sudah di-upload dengan benar.');
    }

    public function preview($id)
    {
        $arsip = ArchiveAccess::applyTo(Arsip::whereKey($id), request()->user())->firstOrFail();
        $path = $this->resolveFilePath($arsip->file_path ?? '');

        if (!$path) {
            return back()->with('error', 'File fisik tidak ditemukan. Pastikan file sudah di-upload dengan benar.');
        }

        ActivityLogger::record('Preview Arsip', 'Melihat pratinjau arsip: ' . $arsip->judul, $arsip);

        return response()->file($path, ['X-Content-Type-Options' => 'nosniff']);
    }

    private function resolveFilePath(string $filePath): ?string
    {
        if ($filePath === '' || $filePath === '-') {
            return null;
        }

        $path = ltrim(str_replace('public/', '', $filePath), '/\\');
        $candidates = [
            Storage::disk('public')->path($path),
            public_path('storage/' . $path),
        ];
        $allowedRoots = [
            realpath(storage_path('app/public')),
            realpath(public_path('storage')),
        ];

        foreach ($candidates as $candidate) {
            $realPath = realpath($candidate);
            if (!$realPath || !is_file($realPath)) {
                continue;
            }

            foreach ($allowedRoots as $root) {
                if ($root && str_starts_with($realPath, $root . DIRECTORY_SEPARATOR)) {
                    return $realPath;
                }
            }
        }

        return null;
    }
}
