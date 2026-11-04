<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Arsip;
use App\Models\ArchiveCategory;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Tambahkan ini untuk kelola file
use Illuminate\Support\Facades\Auth;

class ArsipController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'kategori' => ['nullable', 'string', 'max:100'],
        ], [
            'search.string' => 'Kata kunci pencarian harus berupa teks.',
            'search.max' => 'Kata kunci pencarian maksimal 100 karakter.',
        ]);

        $search = $validated['search'] ?? null;
        $kategori = $validated['kategori'] ?? null;

        $query = Arsip::orderBy('tanggal_dokumen', 'desc');

        // Jika ada pencarian berdasarkan Judul
        if (!empty($search)) {
            $query->where('judul', 'like', '%' . $search . '%');
        }

        // Jika ada filter berdasarkan Kategori
        if (!empty($kategori)) {
            $query->where('kategori', $kategori);
        }

        $arsips = $query->get();

        $categories = ArchiveCategory::where('is_active', true)->orderBy('name')->get();
        return view('admin.arsip.index', compact('arsips', 'search', 'kategori', 'categories'));
    }

    public function create()
{
    // BENAR:
        $categories = ArchiveCategory::where('is_active', true)->orderBy('name')->get();
        return view('admin.arsip.create', compact('categories'));
    
    // SALAH (Ini akan memanggil tampilan form lama yang berantakan!):
    // return view('arsip.create'); 
}

    public function store(Request $request)
{
    // 1. Validasi input form
    $validated = $request->validate([
        'judul' => 'required|string|max:255',
            'nomor_surat' => 'nullable|string|max:100',
            'kategori' => ['required', 'string', 'exists:archive_categories,name,is_active,1'],
        'tanggal_dokumen' => 'required|date',
        'file_arsip' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120', // Maksimal 5MB
        'keterangan' => 'nullable|string|max:5000',
        'classification' => 'required|in:Publik,Internal,Terbatas,Rahasia',
        'allowed_divisions' => 'nullable|string|max:1000',
        'approval_status' => 'required|in:Draft,Menunggu,Disetujui,Ditolak',
        'is_active' => 'required|boolean',
    ]);

    // 2. Logika memproses file upload
    if ($request->hasFile('file_arsip')) {
        $file = $request->file('file_arsip');
        
        // Buat nama file unik agar tidak bentrok (Contoh: 1700123456_SK_Mutasi.pdf)
        $namaFile = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
        
        // Simpan file ke folder storage sementara (akan kita sesuaikan untuk Vercel nanti)
        $path = $file->storeAs('dokumen_arsip', $namaFile, 'public');

        // 3. Simpan data teks dan nama file ke Database PostgreSQL (Supabase)
        $arsip = Arsip::create([
            'user_id' => auth()->id(), // Tambahkan baris ini untuk menyimpan ID admin yang login
            'judul' => $validated['judul'],
            'nomor_surat' => $validated['nomor_surat'] ?? null,
            'kategori' => $validated['kategori'],
            'tanggal_dokumen' => $validated['tanggal_dokumen'],
            'keterangan' => $validated['keterangan'] ?? null,
            'file_path' => $path, // Menyimpan lokasi/nama file di database
            'classification' => $validated['classification'],
            'allowed_divisions' => $this->parseDivisions($validated['allowed_divisions'] ?? ''),
            'approval_status' => $validated['approval_status'],
            'is_active' => $validated['is_active'],
        ]);

        ActivityLogger::record('Tambah Arsip', 'Menambahkan arsip: ' . $arsip->judul, $arsip);

        return redirect()->route('admin.arsip.index')->with('success', 'Arsip dan file berhasil diunggah!');
    }

    return back()->with('error', 'File dokumen wajib diunggah!');
}

    // 4. Fungsi khusus untuk mengunduh file
    public function download($id)
    {
        $arsip = Arsip::findOrFail($id);
        
        // Cek apakah filenya benar-benar ada di server
        if ($arsip->file_path && Storage::disk('public')->exists($arsip->file_path)) {
            ActivityLogger::record('Unduh Arsip', 'Mengunduh arsip: ' . $arsip->judul, $arsip);
            return Storage::disk('public')->download($arsip->file_path);
        }
        
        return back()->with('error', 'Maaf, file fisik dokumen tidak ditemukan di server.');
    }

   // Menampilkan form edit
    public function edit($id)
    {
        $arsip = Arsip::findOrFail($id);
        $categories = ArchiveCategory::where('is_active', true)->orWhere('name', $arsip->kategori)->orderBy('name')->get();
        return view('admin.arsip.edit', compact('arsip', 'categories'));
    }

    // Memproses pembaruan data
    public function update(Request $request, $id)
    {
        $request->validate([
            'judul'            => 'required|string|max:255',
            'nomor_surat'      => 'nullable|string|max:100',
            'kategori'         => ['required', 'string', 'exists:archive_categories,name'],
            'tanggal_dokumen'  => 'required|date',
            'keterangan'       => 'nullable|string',
            'file_arsip'       => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
            'classification'  => 'required|in:Publik,Internal,Terbatas,Rahasia',
            'allowed_divisions' => 'nullable|string|max:1000',
            'approval_status' => 'required|in:Draft,Menunggu,Disetujui,Ditolak',
            'is_active' => 'required|boolean',
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
        $arsip->nomor_surat = $request->nomor_surat;
        $arsip->kategori = $request->kategori;
        $arsip->tanggal_dokumen = $request->tanggal_dokumen;
        $arsip->keterangan = $request->keterangan;
        $arsip->classification = $request->classification;
        $arsip->allowed_divisions = $this->parseDivisions($request->input('allowed_divisions', ''));
        $arsip->approval_status = $request->approval_status;
        $arsip->is_active = $request->boolean('is_active');
        $arsip->save();

        ActivityLogger::record('Ubah Arsip', 'Memperbarui arsip: ' . $arsip->judul, $arsip);

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

        ActivityLogger::record('Hapus Arsip', 'Menghapus arsip: ' . $arsip->judul, $arsip);

        return redirect()->route('admin.arsip.index')->with('success', 'Arsip dan file PDF berhasil dihapus permanen!');
    }
    
    // Fungsi untuk halaman Cetak Laporan (PDF)
    public function print()
    {
        $arsips = Arsip::orderBy('tanggal_dokumen', 'desc')->get();
        ActivityLogger::record('Cetak Arsip', 'Membuka halaman cetak laporan arsip.');
        return view('admin.arsip.print', compact('arsips'));
    }

    // Fungsi untuk Ekspor Data ke Format Excel (CSV)
    public function export()
    {
        $arsips = Arsip::orderBy('tanggal_dokumen', 'desc')->get();
        ActivityLogger::record('Ekspor Arsip', 'Mengekspor daftar arsip ke CSV.');
        $filename = "Laporan_Arsip_Kementerian_" . date('Y-m-d') . ".csv";

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $handle = fopen('php://output', 'w');
        // Tulis Baris Header Excel
        fputcsv($handle, ['No', 'Judul Dokumen', 'Kategori', 'Tanggal Dokumen', 'Keterangan']);

        $no = 1;
        foreach ($arsips as $arsip) {
            fputcsv($handle, [
                $no++,
                $arsip->judul,
                $arsip->kategori,
                $arsip->tanggal_dokumen,
                $arsip->keterangan ?? '-'
            ]);
        }
        
        fclose($handle);
        exit;
    }

    public function show($id)
    {
        // Cari data arsip berdasarkan ID, jika tidak ketemu munculkan 404
        $arsip = Arsip::findOrFail($id);
        
        // Tampilkan halaman detail dan kirimkan data arsip tersebut
        return view('admin.arsip.show', compact('arsip'));
    }

    private function parseDivisions(string $divisions): array
    {
        return array_values(array_unique(array_filter(array_map('trim', explode(',', $divisions)))));
    }
}
