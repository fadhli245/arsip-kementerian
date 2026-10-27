@extends('layouts.app')

@section('title', 'Dashboard Pegawai - SIM Arsip Kementerian Perhubungan Laut')

@section('content')
<div style="display: flex; flex-direction: column; gap: 25px;">

    <!-- Panel Sambutan Pegawai -->
    <div style="background: linear-gradient(135deg, #0A192F 0%, #0284C7 100%); color: white; padding: 35px 40px; border-radius: 16px; box-shadow: 0 10px 25px rgba(2, 132, 199, 0.15); position: relative; overflow: hidden;">
        <div style="position: absolute; right: -20px; bottom: -30px; font-size: 140px; opacity: 0.08; pointer-events: none;">⚓</div>
        <div style="font-size: 11.5px; font-weight: 700; text-transform: uppercase; letter-spacing: 2px; color: #38BDF8; margin-bottom: 8px;">Portal Layanan Pegawai</div>
        <h2 style="font-size: 26px; font-weight: 800    ; margin-bottom: 10px; letter-spacing: -0.5px;">Selamat Datang, Pegawai Kementerian! 👋</h2>
        <p style="font-size: 14.5px; color: #E2E8F0; max-width: 700px; line-height: 1.6;">
            Akses dokumen internal yang diizinkan, cari arsip kedinasan dengan cepat, dan unduh berkas yang Anda butuhkan untuk mendukung kelancaran tugas kedinasan.
        </p>
    </div>

    <!-- Kartu Statistik Khusus Pegawai -->
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px;">
        <div style="background: #FFFFFF; padding: 24px; border-radius: 14px; border: 1px solid #E2E8F0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);">
            <div style="font-size: 11.5px; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">Arsip Dapat Diakses</div>
            <div style="font-size: 30px; font-weight: 800; color: #0F172A;">42</div>
            <div style="font-size: 12px; color: #0284C7; margin-top: 4px; font-weight: 600;">📁 Dokumen aktif internal</div>
        </div>
        <div style="background: #FFFFFF; padding: 24px; border-radius: 14px; border: 1px solid #E2E8F0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);">
            <div style="font-size: 11.5px; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">Kategori Surat</div>
            <div style="font-size: 30px; font-weight: 800; color: #0284C7;">8</div>
            <div style="font-size: 12px; color: #64748B; margin-top: 4px; font-weight: 600;">📑 Klasifikasi dinas</div>
        </div>
        <div style="background: #FFFFFF; padding: 24px; border-radius: 14px; border: 1px solid #E2E8F0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);">
            <div style="font-size: 11.5px; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">Status Akun</div>
            <div style="font-size: 20px; font-weight: 800; color: #16A34A; margin-top: 4px; display: flex; align-items: center; gap: 6px;">
                <span style="width: 10px; height: 10px; background: #16A34A; border-radius: 50%; display: inline-block;"></span> Aktif / Terverifikasi
            </div>
            <div style="font-size: 12px; color: #64748B; margin-top: 4px; font-weight: 600;">🛡️ Hak akses pegawai</div>
        </div>
    </div>

    <!-- Kotak Pencarian Cepat Arsip -->
    <div style="background: #FFFFFF; padding: 25px; border-radius: 14px; border: 1px solid #E2E8F0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);">
        <h3 style="font-size: 15px; font-weight: 700; color: #0F172A; margin-bottom: 12px; display: flex; align-items: center; gap: 8px;">
            <span>🔍</span> Pencarian Cepat Dokumen Kedinasan
        </h3>
        <form action="{{ route('pegawai.arsip.index') }}" method="GET" style="display: flex; gap: 12px;">
            <input type="text" name="cari" placeholder="Ketik judul arsip, nomor surat, atau kata kunci dokumen..." style="flex: 1; padding: 12px 16px; border: 1px solid #CBD5E1; border-radius: 10px; font-size: 14px; outline: none; background: #F8FAFC; color: #0F172A;">
            <button type="submit" style="background: linear-gradient(135deg, #0284C7, #0369A1); color: white; border: none; padding: 0 28px; border-radius: 10px; font-weight: 700; font-size: 13.5px; cursor: pointer; box-shadow: 0 4px 10px rgba(2, 132, 199, 0.2); transition: 0.2s;">
                Cari Arsip
            </button>
        </form>
    </div>

    <!-- Tabel Daftar Arsip Terbaru untuk Pegawai -->
    <div style="background: #FFFFFF; border-radius: 14px; border: 1px solid #E2E8F0; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);">
        <div style="padding: 20px 25px; border-bottom: 1px solid #E2E8F0; display: flex; justify-content: space-between; align-items: center;">
            <h3 style="font-size: 15px; font-weight: 700; color: #0F172A; display: flex; align-items: center; gap: 8px;">
                <span>📁</span> Arsip Kedinasan Terbaru & Siap Unduh
            </h3>
            <a href="{{ route('pegawai.arsip.index') }}" style="font-size: 13px; color: #0284C7; text-decoration: none; font-weight: 700;">Lihat Semua Arsip →</a>
        </div>
        
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 13.5px;">
                <thead>
                    <tr style="background: #F8FAFC; color: #475569; border-bottom: 1px solid #E2E8F0;">
                        <th style="padding: 14px 22px; font-weight: 700;">Judul Dokumen</th>
                        <th style="padding: 14px 22px; font-weight: 700;">Kategori</th>
                        <th style="padding: 14px 22px; font-weight: 700;">Tanggal Dokumen</th>
                        <th style="padding: 14px 22px; font-weight: 700; text-align: center;">Aksi Unduh</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $arsips = \App\Models\Arsip::latest()->take(5)->get();
                    @endphp

                    @forelse($arsips as $arsip)
                    <tr style="border-bottom: 1px solid #F1F5F9; color: #1E293B;">
                        <td style="padding: 15px 22px; font-weight: 600;">{{ $arsip->judul }}</td>
                        <td style="padding: 15px 22px;">
                            <span style="background: #E0F2FE; color: #0369A1; padding: 5px 12px; border-radius: 6px; font-size: 11.5px; font-weight: 700; display: inline-block;">
                                {{ $arsip->kategori }}
                            </span>
                        </td>
                        <td style="padding: 15px 22px; color: #64748B; font-weight: 500;">{{ \Carbon\Carbon::parse($arsip->tanggal_dokumen)->format('d/m/Y') }}</td>
                        <td style="padding: 15px 22px; text-align: center;">
                            <a href="{{ route('pegawai.arsip.download', $arsip->id) }}" style="background: #F0FDF4; color: #16A34A; border: 1px solid #DCFCE7; padding: 7px 14px; border-radius: 8px; text-decoration: none; font-size: 12.5px; font-weight: 700; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 2px 4px rgba(22, 163, 74, 0.1);">
                                📥 Unduh File
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align: center; padding: 40px; color: #94A3B8; font-weight: 500;">Belum ada arsip kedinasan yang tercatat di sistem.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection