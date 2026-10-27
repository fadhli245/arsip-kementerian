@extends('layouts.app')

@section('title', 'Tambah Arsip Baru - SIM Arsip')

@section('content')
<div style="display: flex; flex-direction: column; gap: 20px; max-width: 900px; margin: 0 auto;">

    <!-- Header Form -->
    <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #E2E8F0; padding-bottom: 15px;">
        <div>
            <h2 style="font-size: 22px; font-weight: 800; color: #0A2558; margin: 0;">Tambah Arsip Kedinasan Baru</h2>
            <p style="font-size: 13.5px; color: #64748B; margin: 5px 0 0 0;">Unggah dokumen baru ke dalam pangkalan data (database) Ditjen Hubla.</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" style="background: #F1F5F9; color: #475569; padding: 8px 16px; border-radius: 8px; text-decoration: none; font-size: 13px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px; transition: 0.2s;">
            ⬅️ Kembali
        </a>
    </div>

    <!-- Card Form Utama -->
    <div style="background: #FFFFFF; border-radius: 12px; border: 1px solid #E2E8F0; box-shadow: 0 4px 15px rgba(0,0,0,0.03); padding: 30px;">
        
        <!-- Wajib tambahkan enctype="multipart/form-data" untuk form upload file -->
        <form action="{{ route('admin.arsip.store') }}" method="POST" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 20px;">
            @csrf <!-- Token keamanan bawaan Laravel -->

            <!-- Grid untuk Judul dan Nomor Surat -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div style="display: flex; flex-direction: column; gap: 8px;">
                    <label style="font-size: 13.5px; font-weight: 700; color: #1E293B;">Judul Dokumen <span style="color: #EF4444;">*</span></label>
                    <input type="text" name="judul" required placeholder="Contoh: SK Mutasi Pegawai 2026" style="padding: 12px 15px; border: 1px solid #CBD5E1; border-radius: 8px; font-size: 14px; outline: none; transition: border-color 0.2s;">
                </div>
                <div style="display: flex; flex-direction: column; gap: 8px;">
                    <label style="font-size: 13.5px; font-weight: 700; color: #1E293B;">Nomor Surat</label>
                    <input type="text" name="nomor_surat" placeholder="Contoh: KP.104/1/2/DJPL-26" style="padding: 12px 15px; border: 1px solid #CBD5E1; border-radius: 8px; font-size: 14px; outline: none;">
                </div>
            </div>

            <!-- Grid untuk Kategori dan Tanggal -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div style="display: flex; flex-direction: column; gap: 8px;">
                    <label style="font-size: 13.5px; font-weight: 700; color: #1E293B;">Kategori Arsip <span style="color: #EF4444;">*</span></label>
                    <select name="kategori" required style="padding: 12px 15px; border: 1px solid #CBD5E1; border-radius: 8px; font-size: 14px; outline: none; background: #FFFFFF; cursor: pointer;">
                        <option value="">-- Pilih Klasifikasi --</option>
                        <option value="Surat Masuk">Surat Masuk</option>
                        <option value="Surat Keluar">Surat Keluar</option>
                        <option value="Surat Keputusan (SK)">Surat Keputusan (SK)</option>
                        <option value="Laporan Kegiatan">Laporan Kegiatan</option>
                    </select>
                </div>
                <div style="display: flex; flex-direction: column; gap: 8px;">
                    <label style="font-size: 13.5px; font-weight: 700; color: #1E293B;">Tanggal Dokumen <span style="color: #EF4444;">*</span></label>
                    <input type="date" name="tanggal_dokumen" required style="padding: 12px 15px; border: 1px solid #CBD5E1; border-radius: 8px; font-size: 14px; outline: none;">
                </div>
            </div>

            <!-- Area Upload File -->
            <div style="display: flex; flex-direction: column; gap: 8px;">
                <label style="font-size: 13.5px; font-weight: 700; color: #1E293B;">Unggah File Dokumen <span style="color: #EF4444;">*</span></label>
                <div style="border: 2px dashed #94A3B8; border-radius: 8px; padding: 25px; text-align: center; background: #F8FAFC; cursor: pointer; position: relative;">
                    <div style="font-size: 30px; margin-bottom: 10px;">📄</div>
                    <div style="font-size: 14px; color: #475569; font-weight: 600;">Klik atau seret file ke area ini</div>
                    <div style="font-size: 12px; color: #94A3B8; margin-top: 5px;">Format yang didukung: PDF, DOCX, JPG (Maks. 5MB)</div>
                    <!-- Input file disembunyikan tampilannya tapi memenuhi area -->
                    <input type="file" name="file_arsip" required accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" style="opacity: 0; position: absolute; top: 0; left: 0; width: 100%; height: 100%; cursor: pointer;">
                </div>
            </div>

            <!-- Keterangan Tambahan -->
            <div style="display: flex; flex-direction: column; gap: 8px;">
                <label style="font-size: 13.5px; font-weight: 700; color: #1E293B;">Keterangan / Ringkasan</label>
                <textarea name="keterangan" rows="3" placeholder="Tuliskan ringkasan isi arsip jika diperlukan..." style="padding: 12px 15px; border: 1px solid #CBD5E1; border-radius: 8px; font-size: 14px; outline: none; resize