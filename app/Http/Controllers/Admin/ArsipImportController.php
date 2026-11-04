<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Arsip;
use App\Models\ArchiveCategory;
use App\Services\ActivityLogger;
use Illuminate\Support\Facades\Auth;

class ArsipImportController extends Controller
{
    public function index()
    {
        $categories = ArchiveCategory::where('is_active', true)->orderBy('name')->pluck('name');
        return view('admin.arsip.import', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => ['required', 'array', 'min:1'],
            'judul.*' => ['required', 'string', 'max:255'],
            'kategori' => ['required', 'array'],
            'kategori.*' => ['required', 'string', 'exists:archive_categories,name,is_active,1'],
            'tanggal_dokumen' => ['required', 'array'],
            'tanggal_dokumen.*' => ['required', 'date'],
            'keterangan' => ['nullable', 'array'],
            'keterangan.*' => ['nullable', 'string', 'max:1000'],
        ]);

        foreach ($validated['judul'] as $index => $judul) {
            $arsip = Arsip::create([
                'judul' => trim($judul),
                'kategori' => $validated['kategori'][$index],
                'tanggal_dokumen' => $validated['tanggal_dokumen'][$index],
                'keterangan' => $validated['keterangan'][$index] ?? null,
                'file_path' => '-',
                'user_id' => Auth::id(),
            ]);

            ActivityLogger::record('Impor Arsip', 'Mengimpor arsip: ' . $arsip->judul, $arsip);
        }

        $successCount = count($validated['judul']);

        return redirect()->route('admin.arsip.index')->with('success', "Berhasil menyimpan $successCount data arsip secara massal!");
    }
}
