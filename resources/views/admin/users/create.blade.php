@extends('layouts.app')

@section('title', 'Tambah Arsip Kedinasan Baru - SIM Arsip')

@section('content')
<div style="display: flex; flex-direction: column; gap: 20px; max-width: 900px; margin: 0 auto; width: 100%;">

    <!-- Header Form -->
    <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #E2E8F0; padding-bottom: 15px;">
        <div>
            <h2 style="font-size: 22px; font-weight: 800; color: #0A2558; margin: 0;">Tambah Arsip Kedinasan Baru</h2>
            <p style="font-size: 13.5px; color: #64748B; margin: 5px 0 0 0;">Unggah dokumen baru ke dalam pangkalan data (database) Ditjen Hubla.</p>
        </div>
        <a href="{{ route('admin.arsip.index') }}" style="background-color: #0A2558; color: #FFFFFF; font-weight: 700; font-size: 13px; padding: 10px 18px; border-radius: 8px; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 4px 6px rgba(10, 37, 88, 0.15);">
            ← Kembali
        </a>
    </div>

    <!-- Card Form Utama -->
    <div style="background: #FFFFFF; border-radius: 12px; border: 1px solid #E2E8F0; box-shadow: 0 4px 15px rgba(0,0,0,0.03); padding: 30px;">
        
        <form action="{{ route('admin.arsip.store') }}" method="POST" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 20px;">
            @csrf

            <!-- Grid Judul & Nomor Surat -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div style="display: flex; flex-direction: column; gap: 8px;">
                    <label style="font-size: 13.5px; font-weight: 700; color: #1E293B;">Judul Dokumen <span style="color: #EF4444;">*</span></label>
                    <input type="text" name="judul" value="{{ old('judul') }}" required placeholder="Contoh: SK Mutasi Pegawai 2026" style="padding: 12px 15px; border: 1px solid #CBD5E1; border-radius: 8px; font-size: 14px; outline: none; background: #FFFFFF;">
                </div>
                <div style="display: flex; flex-direction: column; gap: 8px;">
                    <label style="font-size: 13.5px; font-weight: 700; color: #1E293B;">Nomor Surat</label>
                    <input type="text" name="nomor_surat" value="{{ old('nomor_surat') }}" placeholder="Contoh: KP.104/1/2/DJPL-26" style="padding: 12px 15px; border: 1px solid #CBD5E1; border-radius: 8px; font-size: 14px; outline: none; background: #FFFFFF;">
                </div>
            </div>

            <!-- Grid Kategori & Tanggal -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div style="display: flex; flex-direction: column; gap: 8px;">
                    <label style="font-size: 13.5px; font-weight: 700; color: #1E293B;">Kategori Arsip <span style="color: #EF4444;">*</span></label>
                    <select name="kategori" required style="padding: 12px 15px; border: 1px solid #CBD5E1; border-radius: 8px; font-size: 14px; outline: none; background: #FFFFFF; cursor: pointer;">
                        <option value="">-- Pilih Klasifikasi --</option>
                        <option value="Surat Masuk" {{ old('kategori') == 'Surat Masuk' ? 'selected' : '' }}>Surat Masuk</option>
                        <option value="Surat Keluar" {{ old('kategori') == 'Surat Keluar' ? 'selected' : '' }}>Surat Keluar</option>
                        <option value="Surat Keputusan (SK)" {{ old('kategori') == 'Surat Keputusan (SK)' ? 'selected' : '' }}>Surat Keputusan (SK)</option>
                        <option value="Laporan Kegiatan" {{ old('kategori') == 'Laporan Kegiatan' ? 'selected' : '' }}>Laporan Kegiatan</option>
                    </select>
                </div>
                <div style="display: flex; flex-direction: column; gap: 8px;">
                    <label style="font-size: 13.5px; font-weight: 700; color: #1E293B;">Tanggal Dokumen <span style="color: #EF4444;">*</span></label>
                    <input type="date" name="tanggal_dokumen" value="{{ old('tanggal_dokumen') }}" required style="padding: 12px 15px; border: 1px solid #CBD5E1; border-radius: 8px; font-size: 14px; outline: none; background: #FFFFFF;">
                </div>
            </div>

            <!-- Area Upload File -->
            <div style="display: flex; flex-direction: column; gap: 8px;">
                <label style="font-size: 13.5px; font-weight: 700; color: #1E293B;">Unggah File Dokumen <span style="color: #EF4444;">*</span></label>
                <div style="border: 2px dashed #0A2558; border-radius: 8px; padding: 25px; text-align: center; background: #F8FAFC; position: relative;">
                    <div style="font-size: 30px; margin-bottom: 8px;">📄</div>
                    <div style="font-size: 14px; color: #0A2558; font-weight: 700;">Klik atau seret file ke area ini</div>
                    <div style="font-size: 12px; color: #64748B; margin-top: 4px;">Format yang didukung: PDF, DOCX, JPG (Maks. 5MB)</div>
                    <input type="file" name="file_arsip" required accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" style="opacity: 0; position: absolute; top: 0; left: 0; width: 100%; height: 100%; cursor: pointer;">
                </div>
            </div>

            <!-- Keterangan / Ringkasan (KOSONG DAN BERSIH TANPA CODE HTML) -->
            <div style="display: flex; flex-direction: column; gap: 8px;">
                <label style="font-size: 13.5px; font-weight: 700; color: #1E293B;">Keterangan / Ringkasan</label>
                <textarea name="keterangan" rows="3" placeholder="Tuliskan ringkasan isi arsip jika diperlukan..." style="padding: 12px 15px; border: 1px solid #CBD5E1; border-radius: 8px; font-size: 14px; outline: none; resize: vertical; background: #FFFFFF; color: #0F172A;">{{ old('keterangan') }}</textarea>
            </div>

            <!-- Tombol Aksi -->
            <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 10px; border-top: 1px solid #E2E8F0; padding-top: 20px;">
                <button type="reset" style="background: #FFFFFF; color: #64748B; border: 1px solid #CBD5E1; padding: 10px 20px; border-radius: 8px; font-weight: 700; font-size: 13.5px; cursor: pointer;">
                    Reset Form
                </button>
                <button type="submit" style="background-color: #0A2558; color: #FFFFFF; border: none; padding: 10px 28px; border-radius: 8px; font-weight: 700; font-size: 13.5px; cursor: pointer; box-shadow: 0 4px 10px rgba(10, 37, 88, 0.2);">
                    💾 Simpan Arsip
                </button>
            </div>

        </form>
    </div>
</div>
@endsection