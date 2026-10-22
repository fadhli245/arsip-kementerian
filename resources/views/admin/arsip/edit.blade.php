@extends('layouts.app')

@section('title', 'Edit Arsip - SIM Arsip')

@section('content')
<style>
    .form-container { background: white; border: 1px solid #e2eaf2; border-radius: 14px; padding: 30px; max-width: 700px; margin: 0 auto; box-shadow: 0 5px 20px rgba(22,49,78,.03); }
    .form-header { margin-bottom: 25px; border-bottom: 1px solid #e2eaf2; padding-bottom: 15px; }
    .form-header h2 { color: var(--navy); font-family: 'Manrope', sans-serif; font-size: 22px; }
    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; font-size: 13px; font-weight: 700; color: var(--navy); margin-bottom: 8px; }
    .form-control { width: 100%; padding: 12px; border: 1px solid var(--line); border-radius: 8px; font-size: 14px; background: #fff; outline: none; }
    .form-control:focus { border-color: var(--blue); box-shadow: 0 0 0 3px rgba(36,92,145,.1); }
    .form-actions { display: flex; justify-content: flex-end; gap: 12px; margin-top: 30px; }
    .btn-secondary { background: #f0f4f8; color: var(--muted); border: 1px solid var(--line); padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: 700; font-size: 13px; }
    .btn-submit { background: linear-gradient(100deg, #163e68, #245c91); color: white; border: none; padding: 10px 24px; border-radius: 8px; font-weight: 700; font-size: 13px; cursor: pointer; }
    .error-msg { color: var(--red); font-size: 12px; margin-top: 5px; display: block; }
    .file-info { font-size: 12px; color: var(--green); margin-top: 5px; font-weight: 600; }
</style>

<div class="form-container">
    <div class="form-header">
        <h2>Ubah Data Arsip</h2>
    </div>

    <!-- Perhatikan method PUT dan enctype multipart di sini -->
    <form action="{{ route('admin.arsip.update', $arsip->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>NOMOR DOKUMEN / SURAT</label>
            <input type="text" name="nomor_dokumen" class="form-control" value="{{ old('nomor_dokumen', $arsip->nomor_dokumen) }}" required>
            @error('nomor_dokumen') <span class="error-msg">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label>JUDUL ARSIP</label>
            <input type="text" name="judul" class="form-control" value="{{ old('judul', $arsip->judul) }}" required>
        </div>

        <div class="form-group">
            <label>KATEGORI DOKUMEN</label>
            <select name="kategori" class="form-control" required>
                <option value="Surat Masuk" {{ $arsip->kategori == 'Surat Masuk' ? 'selected' : '' }}>Surat Masuk</option>
                <option value="Surat Keluar" {{ $arsip->kategori == 'Surat Keluar' ? 'selected' : '' }}>Surat Keluar</option>
                <option value="Surat Keputusan (SK)" {{ $arsip->kategori == 'Surat Keputusan (SK)' ? 'selected' : '' }}>Surat Keputusan (SK)</option>
                <option value="Laporan Kegiatan" {{ $arsip->kategori == 'Laporan Kegiatan' ? 'selected' : '' }}>Laporan Kegiatan</option>
            </select>
        </div>

        <div class="form-group">
            <label>TANGGAL DOKUMEN</label>
            <input type="date" name="tanggal_dokumen" class="form-control" value="{{ old('tanggal_dokumen', $arsip->tanggal_dokumen) }}" required>
        </div>

        <div class="form-group">
            <label>GANTI DOKUMEN PDF (OPSIONAL)</label>
            <input type="file" name="file_arsip" class="form-control" accept=".pdf" style="padding: 9px;">
            <div class="file-info">*Biarkan kosong jika tidak ingin mengganti file PDF yang sudah ada.</div>
            @error('file_arsip') <span class="error-msg">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label>KETERANGAN (OPSIONAL)</label>
            <textarea name="keterangan" class="form-control" rows="3">{{ old('keterangan', $arsip->keterangan) }}</textarea>
        </div>

        <div class="form-actions">
            <a href="{{ route('admin.arsip.index') }}" class="btn-secondary">Batal</a>
            <button type="submit" class="btn-submit">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection