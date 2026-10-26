<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Arsip;
use Illuminate\Support\Facades\Auth;

class ArsipImportController extends Controller
{
    public function index()
    {
        return view('admin.arsip.import');
    }

    public function store(Request $request)
    {
        $successCount = 0;

        if ($request->has('judul')) {
            $juduls = $request->input('judul');
            $kategoris = $request->input('kategori');
            $tanggals = $request->input('tanggal_dokumen');
            $keterangans = $request->input('keterangan');

            foreach ($juduls as $index => $judul) {
                if (!empty(trim($judul))) {
                    Arsip::create([
                        'judul'           => $judul,
                        'kategori'        => $kategoris[$index] ?? 'Surat Masuk',
                        'tanggal_dokumen' => !empty($tanggals[$index]) ? $tanggals[$index] : now(),
                        'keterangan'      => $keterangans[$index] ?? 'Input Massal Web',
                        'file_path'       => '-',
                        'user_id'         => Auth::id() ?? 1,
                    ]);
                    $successCount++;
                }
            }

            if ($successCount > 0) {
                return redirect()->route('admin.arsip.index')->with('success', "Berhasil menyimpan $successCount data arsip secara massal!");
            }
        }

        return back()->with('error', 'Tidak ada data valid yang diproses.');
    }
}