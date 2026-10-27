@extends('layouts.app')

@section('title', 'Dashboard Admin - SIM Arsip Kementerian Perhubungan Laut')

@section('content')
<div style="display: flex; flex-direction: column; gap: 25px;">

    <!-- Panel Sambutan Admin & Aksi Cepat -->
    <div style="background: linear-gradient(135deg, #0A2558 0%, #153A7A 100%); color: white; padding: 30px 40px; border-radius: 16px; box-shadow: 0 10px 25px rgba(10, 37, 88, 0.15); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
        <div>
            <div style="font-size: 11.5px; font-weight: 700; text-transform: uppercase; letter-spacing: 2px; color: #F2C94C; margin-bottom: 8px;">Portal Administrator</div>
            <h2 style="font-size: 24px; font-weight: 800; margin-bottom: 5px; letter-spacing: -0.5px;">Dashboard Pengelolaan Arsip</h2>
            <p style="font-size: 14px; color: #E2E8F0; margin: 0; opacity: 0.9;">
                Kelola dokumen masuk, keluar, dan keputusan untuk seluruh lingkungan instansi.
            </p>
        </div>
        <div>
            <!-- Tombol Aksi Cepat untuk CRUD -->
            <a href="{{ route('admin.arsip.create') }}" style="background: #F2C94C; color: #0A2558; border: none; padding: 12px 24px; border-radius: 10px; font-weight: 700; font-size: 14px; cursor: pointer; box-shadow: 0 4px 10px rgba(242, 201, 76, 0.3); text-decoration: none; display: inline-flex; align-items: center; gap: 8px; transition: 0.2s;">
                <span>➕</span> Tambah Arsip Baru
            </a>
        </div>
    </div>

    <!-- Kartu Statistik Admin (Sesuai Variabel Controller) -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 20px;">
        <!-- Total Keseluruhan -->
        <div style="background: #FFFFFF; padding: 24px; border-radius: 14px; border: 1px solid #E2E8F0; border-bottom: 4px solid #0A2558; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);">
            <div style="font-size: 11.5px; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">Total Arsip</div>
            <div style="font-size: 32px; font-weight: 800; color: #0F172A;">{{ $totalArsip }}</div>
        </div>
        
        <!-- Kategori: Surat Masuk -->
        <div style="background: #FFFFFF; padding: 24px; border-radius: 14px; border: 1px solid #E2E8F0; border-bottom: 4px solid #0284C7; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);">
            <div style="font-size: 11.5px; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">Surat Masuk</div>
            <div style="font-size: 32px; font-weight: 800; color: #0284C7;">{{ $suratMasuk }}</div>
        </div>

        <!-- Kategori: Surat Keluar -->
        <div style="background: #FFFFFF; padding: 24px; border-radius: 14px; border: 1px solid #E2E8F0; border-bottom: 4px solid #16A34A; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);">
            <div style="font-size: 11.5px; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">Surat Keluar</div>
            <div style="font-size: 32px; font-weight: 800; color: #16A34A;">{{ $suratKeluar }}</div>
        </div>

        <!-- Kategori: Surat Keputusan -->
        <div style="background: #FFFFFF; padding: 24px; border-radius: 14px; border: 1px solid #E2E8F0; border-bottom: 4px solid #EAB308; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);">
            <div style="font-size: 11.5px; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">SK / Keputusan</div>
            <div style="font-size: 32px; font-weight: 800; color: #EAB308;">{{ $sk }}</div>
        </div>

        <!-- Kategori: Laporan -->
        <div style="background: #FFFFFF; padding: 24px; border-radius: 14px; border: 1px solid #E2E8F0; border-bottom: 4px solid #8B5CF6; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);">
            <div style="font-size: 11.5px; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">Laporan Kegiatan</div>
            <div style="font-size: 32px; font-weight: 800; color: #8B5CF6;">{{ $laporan }}</div>
        </div>
    </div>

    <!-- Tabel Daftar Arsip Terbaru -->
    <div style="background: #FFFFFF; border-radius: 14px; border: 1px solid #E2E8F0; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);">
        <div style="padding: 20px 25px; border-bottom: 1px solid #E2E8F0; display: flex; justify-content: space-between; align-items: center;">
            <h3 style="font-size: 15px; font-weight: 700; color: #0F172A; display: flex; align-items: center; gap: 8px; margin: 0;">
                <span>📋</span> 5 Arsip Terakhir Ditambahkan
            </h3>
            <a href="{{ route('admin.arsip.index') }}" style="font-size: 13px; color: #0A2558; text-decoration: none; font-weight: 700;">Kelola Semua Arsip →</a>
        </div>
        
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 13.5px;">
                <thead>
                    <tr style="background: #F8FAFC; color: #475569; border-bottom: 1px solid #E2E8F0;">
                        <th style="padding: 14px 22px; font-weight: 700;">Judul Dokumen</th>
                        <th style="padding: 14px 22px; font-weight: 700;">No. Surat</th>
                        <th style="padding: 14px 22px; font-weight: 700;">Kategori</th>
                        <th style="padding: 14px 22px; font-weight: 700;">Tanggal Upload</th>
                        <th style="padding: 14px 22px; font-weight: 700; text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($arsipTerbaru as $arsip)
                    <tr style="border-bottom: 1px solid #F1F5F9; color: #1E293B;">
                        <td style="padding: 15px 22px; font-weight: 600;">{{ $arsip->judul }}</td>
                        <td style="padding: 15px 22px; color: #64748B;">{{ $arsip->nomor_surat ?? '-' }}</td>
                        <td style="padding: 15px 22px;">
                            <span style="background: #E0F2FE; color: #0369A1; padding: 5px 12px; border-radius: 6px; font-size: 11.5px; font-weight: 700; display: inline-block;">
                                {{ $arsip->kategori }}
                            </span>
                        </td>
                        <td style="padding: 15px 22px; color: #64748B; font-weight: 500;">{{ $arsip->created_at->diffForHumans() }}</td>
                        <td style="padding: 15px 22px; text-align: right;">
                           <!-- Ubah sementara menjadi tanda pagar (#) -->
<a href="#" style="color: #F59E0B; text-decoration: none; font-weight: 600; margin-right: 15px;">✏️ Edit</a>
<a href="#" style="color: #0284C7; text-decoration: none; font-weight: 600;">👁️ Detail</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 40px; color: #94A3B8; font-weight: 500;">Belum ada arsip yang diinput oleh Admin.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection