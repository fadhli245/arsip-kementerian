@extends('layouts.app')

@section('title', 'Edit Data Arsip - SIM Arsip')

@section('content')
<div style="display: flex; flex-direction: column; gap: 20px; max-width: 900px; margin: 0 auto; width: 100%;">

    <!-- Header Form -->
    <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #E2E8F0; padding-bottom: 15px;">
        <div>
            <h2 style="font-size: 22px; font-weight: 800; color: #0A2558; margin: 0;">Ubah Data Arsip Kedinasan</h2>
            <p style="font-size: 13.5px; color: #64748B; margin: 5px 0 0 0;">Perbarui informasi atau ganti dokumen dari arsip yang sudah ada.</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" style="background-color: #F8FAFC; color: #0A2558; border: 1px solid #CBD5E1; font-weight: 700; font-size: 13px; padding: 10px 18px; border-radius: 8px; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; transition: 0.2s;">
            ← Kembali
        </a>
    </div>

    <!-- Card Form Utama -->
    <div style="background: #FFFFFF; border-radius: 12px; border: 1px solid #E2E8F0; box-shadow: 0 4px 15px rgba(0,0,0,0.03); padding: 30px;">
        
        <form action="{{ route('admin.arsip.update', $arsip->id) }}" method="POST" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 20px;">
            @csrf
            @method('PUT')

            <!-- Grid Judul & Nomor Surat -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div style="display: flex; flex-direction: column; gap: 8px;">
                    <label style="font-size: 13.5px; font-weight: 700; color: #1E293B;">Judul Dokumen <span style="color: #EF4444;">*</span></label>
                    <input type="text" name="judul" value="{{ old('judul', $arsip->judul) }}" required style="padding: 12px 15px; border: 1px solid #CBD5E1; border-radius: 8px; font-size: 14px; outline: none; background: #FFFFFF;">
                </div>
                <div style="display: flex; flex-direction: column; gap: 8px;">
                    <label style="font-size: 13.5px; font-weight: 700; color: #1E293B;">Nomor Surat</label>
                    <input type="text" name="nomor_surat" value="{{ old('nomor_surat', $arsip->nomor_surat) }}" style="padding: 12px 15px; border: 1px solid #CBD5E1; border-radius: 8px; font-size: 14px; outline: none; background: #FFFFFF;">
                </div>
            </div>

            <!-- Grid Kategori & Tanggal -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div style="display: flex; flex-direction: column; gap: 8px;">
                    <label style="font-size: 13.5px; font-weight: 700; color: #1E293B;">Kategori Arsip <span style="color: #EF4444;">*</span></label>
                    <select name="kategori" required style="padding: 12px 15px; border: 1px solid #CBD5E1; border-radius: 8px; font-size: 14px; outline: none; background: #FFFFFF; cursor: pointer;">
                        <option value="Surat Masuk" {{ old('kategori', $arsip->kategori) == 'Surat Masuk' ? 'selected' : '' }}>Surat Masuk</option>
                        <option value="Surat Keluar" {{ old('kategori', $arsip->kategori) == 'Surat Keluar' ? 'selected' : '' }}>Surat Keluar</option>
                        <option value="Surat Keputusan (SK)" {{ old('kategori', $arsip->kategori) == 'Surat Keputusan (SK)' ? 'selected' : '' }}>Surat Keputusan (SK)</option>
                        <option value="Laporan Kegiatan" {{ old('kategori', $arsip->kategori) == 'Laporan Kegiatan' ? 'selected' : '' }}>Laporan Kegiatan</option>
                    </select>
                </div>
                <div style="display: flex; flex-direction: column; gap: 8px;">
                    <label style="font-size: 13.5px; font-weight: 700; color: #1E293B;">Tanggal Dokumen <span style="color: #EF4444;">*</span></label>
                    <input type="date" name="tanggal_dokumen" value="{{ old('tanggal_dokumen', $arsip->tanggal_dokumen) }}" required style="padding: 12px 15px; border: 1px solid #CBD5E1; border-radius: 8px; font-size: 14px; outline: none; background: #FFFFFF;">
                </div>
            </div>

            <!-- Area Upload File -->
            <div style="display: flex; flex-direction: column; gap: 8px;">
                <label style="font-size: 13.5px; font-weight: 700; color: #1E293B;">Ganti File Dokumen <span style="color: #64748B; font-weight: 500;">(Opsional)</span></label>
                <div style="border: 2px dashed #0A2558; border-radius: 8px; padding: 25px; text-align: center; background: #F8FAFC; position: relative;">
                    <div style="font-size: 30px; margin-bottom: 8px;">📄</div>
                    <div style="font-size: 14px; color: #0A2558; font-weight: 700;">Klik atau seret file baru ke area ini</div>
                    <div style="font-size: 12px; color: #64748B; margin-top: 4px;">Biarkan kosong jika tidak ingin mengganti file PDF yang sudah ada.</div>
                    <input type="file" name="file_arsip" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" style="opacity: 0; position: absolute; top: 0; left: 0; width: 100%; height: 100%; cursor: pointer;">
                </div>
            </div>

            <!-- Keterangan / Ringkasan -->
            <div style="display: flex; flex-direction: column; gap: 8px;">
                <label style="font-size: 13.5px; font-weight: 700; color: #1E293B;">Keterangan / Ringkasan</label>
                <textarea name="keterangan" rows="3" style="padding: 12px 15px; border: 1px solid #CBD5E1; border-radius: 8px; font-size: 14px; outline: none; resize: vertical; background: #FFFFFF; color: #0F172A;">{{ old('keterangan', $arsip->keterangan) }}</textarea>
            </div>

            <!-- Tombol Aksi -->
            <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 10px; border-top: 1px solid #E2E8F0; padding-top: 20px;">
                <a href="{{ route('admin.dashboard') }}" style="background: #FFFFFF; color: #64748B; border: 1px solid #CBD5E1; padding: 10px 20px; border-radius: 8px; font-weight: 700; font-size: 13.5px; cursor: pointer; text-decoration: none;">
                    Batal
                </a>
                <button type="submit" style="background-color: #0A2558; color: #FFFFFF; border: none; padding: 10px 28px; border-radius: 8px; font-weight: 700; font-size: 13.5px; cursor: pointer; box-shadow: 0 4px 10px rgba(10, 37, 88, 0.2);">
                    💾 Simpan Perubahan
                </button>
            </div>

        </form>
    </div>
</div>
@endsection