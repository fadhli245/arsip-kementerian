@extends('layouts.app')

@section('title', 'Tambah Arsip Baru - SIM Arsip')

@section('content')
<style>
    .form-container { background: white; border: 1px solid #e2eaf2; border-radius: 14px; padding: 30px; max-width: 700px; margin: 0 auto; box-shadow: 0 5px 20px rgba(22,49,78,.03); }
    .form-header { margin-bottom: 25px; border-bottom: 1px solid #e2eaf2; padding-bottom: 15px; }
    .form-header h2 { color: var(--navy); font-family: 'Manrope', sans-serif; font-size: 22px; }
    .form-header p { color: var(--muted); font-size: 13px; margin-top: 4px; }
    
    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; font-size: 13px; font-weight: 700; color: var(--navy); margin-bottom: 8px; }
    .form-control { width: 100%; padding: 12px; border: 1px solid var(--line); border-radius: 8px; font-size: 14px; font-family: inherit; background: #fff; outline: none; }
    .form-control:focus { border-color: var(--blue); box-shadow: 0 0 0 3px rgba(36,92,145,.1); }
    
    .form-actions { display: flex; justify-content: flex-end; gap: 12px; margin-top: 30px; }
    .btn-secondary { background: #f0f4f8; color: var(--muted); border: 1px solid var(--line); padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: 700; font-size: 13px; }
    .btn-submit { background: linear-gradient(100deg, #163e68, #245c91); color: white; border: none; padding: 10px 24px; border-radius: 8px; font-weight: 700; font-size: 13px; cursor: pointer; }
    .btn-submit:hover { filter: brightness(1.1); }
    
    .error-msg { color: var(--red); font-size: 12px; margin-top: 5px; display: block; }
</style>

<div class="form-container">
    <div class="form-header">
        <h2>Tambah Arsip Baru</h2>
        <p>Isi formulir berikut untuk mendaftarkan dokumen resmi ke dalam sistem.</p>
    </div>

    <form action="{{ route('admin.arsip.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

       <div class="form-group">
    <label for="file_arsip">UNGGAH DOKUMEN (Wajib PDF, Maks 5MB)</label>
    <input type="file" id="file_arsip" name="file_arsip" class="form-control" accept=".pdf" required style="padding: 9px;">
    @error('file_arsip')
        <span class="error-msg">{{ $message }}</span>
    @enderror
    </div>

        <div class="form-group">
            <label for="judul">JUDUL ARSIP</label>
            <input type="text" id="judul" name="judul" class="form-control" value="{{ old('judul') }}" placeholder="Masukkan judul atau perihal dokumen" required>
            @error('judul')
                <span class="error-msg">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="kategori">KATEGORI DOKUMEN</label>
            <select id="kategori" name="kategori" class="form-control" required>
                <option value="">-- Pilih Kategori --</option>
                <option value="Surat Masuk" {{ old('kategori') == 'Surat Masuk' ? 'selected' : '' }}>Surat Masuk</option>
                <option value="Surat Keluar" {{ old('kategori') == 'Surat Keluar' ? 'selected' : '' }}>Surat Keluar</option>
                <option value="Surat Keputusan (SK)" {{ old('kategori') == 'Surat Keputusan (SK)' ? 'selected' : '' }}>Surat Keputusan (SK)</option>
                <option value="Laporan Kegiatan" {{ old('kategori') == 'Laporan Kegiatan' ? 'selected' : '' }}>Laporan Kegiatan</option>
            </select>
            @error('kategori')
                <span class="error-msg">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="tanggal_dokumen">TANGGAL DOKUMEN</label>
            <input type="date" id="tanggal_dokumen" name="tanggal_dokumen" class="form-control" value="{{ old('tanggal_dokumen') }}" required>
            @error('tanggal_dokumen')
                <span class="error-msg">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="keterangan">KETERANGAN / RINGKASAN (OPSIONAL)</label>
            <textarea id="keterangan" name="keterangan" class="form-control" rows="3" placeholder="Tambahkan catatan singkat mengenai arsip ini...">{{ old('keterangan') }}</textarea>
        </div>

        <div class="form-actions">
            <a href="{{ route('admin.arsip.index') }}" class="btn-secondary">Batal</a>
            <button type="submit" class="btn-submit">Simpan Arsip</button>
        </div>
    </form>
</div>
@endsection