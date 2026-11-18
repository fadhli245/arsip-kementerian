<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\Arsip;
use App\Models\ArchiveCategory;
use App\Services\ActivityLogger;
use App\Services\ArchiveAccess;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArsipController extends Controller
{
    // Menampilkan halaman pencarian & daftar arsip untuk pegawai
    public function index(Request $request)
    {
        $validated = $request->validate([
            'q' => ['nullable', 'string', 'min:2', 'max:100'],
            'search' => ['nullable', 'string', 'min:2', 'max:100'],
            'kategori' => ['nullable', 'string', 'max:100'],
            'favorit' => ['nullable', 'boolean'],
        ], [
            'search.string' => 'Kata kunci pencarian harus berupa teks.',
            'search.max' => 'Kata kunci pencarian maksimal 100 karakter.',
            'q.min' => 'Kata kunci pencarian minimal 2 karakter.',
            'search.min' => 'Kata kunci pencarian minimal 2 karakter.',
        ]);

        $query = ArchiveAccess::applyTo(Arsip::query(), $request->user());

        // Fitur Pencarian berdasarkan Kata Kunci (Judul atau Nomor Surat)
        $searchTerm = $validated['q'] ?? $validated['search'] ?? null;
        if (!empty($searchTerm)) {
            $query->where(function($q) use ($searchTerm) {
                $q->where('judul', 'like', '%' . $searchTerm . '%')
                  ->orWhere('nomor_surat', 'like', '%' . $searchTerm . '%')
                  ->orWhere('keterangan', 'like', '%' . $searchTerm . '%');
            });
        }

        // Fitur Filter berdasarkan Kategori
        if (!empty($validated['kategori'])) {
            $query->where('kategori', $validated['kategori']);
        }
        if (!empty($validated['favorit'])) {
            $query->whereHas('favoritedBy', fn ($favoriteQuery) => $favoriteQuery->where('users.id', $request->user()->id));
        }

        // Ambil data terbaru
        $arsips = $query->latest()->paginate(12)->withQueryString();
        $kategoriList = ArchiveCategory::where('is_active', true)->orderBy('name')->pluck('name');

        return view('pegawai.arsip.index', compact('arsips', 'kategoriList'));
    }

    // Fungsi bagi Pegawai untuk mengunduh PDF (Versi Direct Path)
    public function download($id)
    {
        $arsip = $this->authorizedArchive($id);

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
        $arsip = $this->authorizedArchive($id);
        $path = $this->resolveFilePath($arsip->file_path ?? '');

        if (!$path) {
            return back()->with('error', 'File fisik tidak ditemukan. Pastikan file sudah di-upload dengan benar.');
        }

        return response()->file($path, ['X-Content-Type-Options' => 'nosniff']);
    }

    public function logPreview(int $id): JsonResponse
    {
        $arsip = $this->authorizedArchive($id);
        ActivityLogger::record('Preview Arsip', 'Melihat pratinjau arsip: ' . $arsip->judul, $arsip);

        return response()->json(['message' => 'Aktivitas pratinjau tercatat.']);
    }

    public function addFavorite(int $id): JsonResponse
    {
        $arsip = $this->authorizedArchive($id);
        request()->user()->favorites()->syncWithoutDetaching([$arsip->id]);

        return response()->json(['favorit' => true, 'message' => 'Arsip ditambahkan ke favorit.']);
    }

    public function removeFavorite(int $id): JsonResponse
    {
        $arsip = $this->authorizedArchive($id);
        request()->user()->favorites()->detach($arsip->id);

        return response()->json(['favorit' => false, 'message' => 'Arsip dihapus dari favorit.']);
    }

    private function resolveFilePath(string $filePath): ?string
    {
        if ($filePath === '' || $filePath === '-') {
            return null;
        }

        $path = ltrim(str_replace('public/', '', $filePath), '/\\');
        $candidates = [
            Storage::disk('local')->path($path),
            Storage::disk('public')->path($path),
            public_path('storage/' . $path),
        ];
        $allowedRoots = [
            realpath(storage_path('app')),
            realpath(storage_path('app/public')),
            realpath(public_path('storage')),
        ];

        foreach ($candidates as $candidate) {
            $realPath = realpath($candidate);
            if (!$realPath || !is_file($realPath)) {
                continue;
            }

            foreach ($allowedRoots as $root) {
                if ($root && ($realPath === $root || str_starts_with($realPath, $root . DIRECTORY_SEPARATOR))) {
                    return $realPath;
                }
            }
        }

        return null;
    }

    private function authorizedArchive(int|string $id): Arsip
    {
        $arsip = Arsip::findOrFail($id);
        abort_unless(ArchiveAccess::allows($arsip, request()->user()), 403);

        return $arsip;
    }
}
