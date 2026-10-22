@extends('layouts.app')

@section('title', 'Dashboard Admin - SIM Arsip')

@section('content')
<style>
    /* Hero Banner */
    .hero-banner {
        background: linear-gradient(135deg, #102d4f 0%, #1c4e80 100%);
        color: white;
        padding: 30px 35px;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(16, 45, 79, 0.15);
        margin-bottom: 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 20px;
    }
    .hero-text h1 { font-family: 'Manrope', sans-serif; font-size: 26px; font-weight: 800; margin-bottom: 8px; }
    .hero-text p { color: #d3e0ed; font-size: 14px; max-width: 600px; line-height: 1.6; }
    .hero-btn { background: #245c91; color: white; border: 1px solid rgba(255,255,255,0.3); padding: 12px 22px; border-radius: 12px; font-weight: 700; font-size: 13px; text-decoration: none; transition: 0.2s; }
    .hero-btn:hover { background: white; color: #102d4f; }

    /* Grid Statistik Utama */
    .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 35px; }
    .stat-card { background: white; border: 1px solid #e2eaf2; border-radius: 16px; padding: 22px; box-shadow: 0 4px 15px rgba(22, 49, 78, 0.03); display: flex; align-items: center; justify-content: space-between; transition: transform 0.2s; }
    .stat-card:hover { transform: translateY(-3px); }
    .stat-info .label { font-size: 12px; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px; }
    .stat-info .number { font-size: 28px; font-family: 'Manrope', sans-serif; font-weight: 800; color: var(--navy); }
    .stat-icon { width: 50px; height: 50px; border-radius: 14px; display: grid; place-items: center; font-size: 22px; }

    /* Variasi Warna Kartu */
    .icon-blue { background: #eef5fb; color: #245c91; }
    .icon-green { background: #edf9f2; color: #176b49; }
    .icon-orange { background: #fff8ec; color: #d97706; }
    .icon-purple { background: #f3f0ff; color: #6d28d9; }

    /* Layout 2 Kolom untuk Aktivitas & Breakdown */
    .dashboard-layout { display: grid; grid-template-columns: 2fr 1fr; gap: 25px; }
    @media (max-width: 900px) { .dashboard-layout { grid-template-columns: 1fr; } }

    /* Panel Card */
    .panel-card { background: white; border: 1px solid #e2eaf2; border-radius: 18px; padding: 25px; box-shadow: 0 4px 15px rgba(22, 49, 78, 0.03); }
    .panel-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    .panel-header h3 { font-family: 'Manrope', sans-serif; font-size: 17px; color: var(--navy); font-weight: 700; }
    .panel-header a { font-size: 13px; color: var(--blue); font-weight: 700; text-decoration: none; }

    /* Tabel Mini Arsip Terbaru */
    .mini-table { width: 100%; border-collapse: collapse; }
    .mini-table th { text-align: left; font-size: 11px; text-transform: uppercase; color: var(--muted); padding: 12px; border-bottom: 1px solid #e2eaf2; letter-spacing: 0.5px; }
    .mini-table td { padding: 14px 12px; font-size: 13px; border-bottom: 1px solid #f0f4f8; }
    .mini-table tr:last-child td { border-bottom: none; }
    .doc-num { font-weight: 700; color: var(--navy); }

    /* Badge Kategori */
    .badge { padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 700; display: inline-block; }
    .badge-surat { background: #edf9f2; color: #176b49; }
    .badge-laporan { background: #eef5fb; color: #245c91; }
    .badge-sk { background: #f3f0ff; color: #6d28d9; }

    /* Progress Bar Kategori */
    .cat-progress-item { margin-bottom: 18px; }
    .cat-progress-label { display: flex; justify-content: space-between; font-size: 13px; font-weight: 600; margin-bottom: 6px; }
    .progress-bar-bg { background: #f0f4f8; height: 8px; border-radius: 10px; overflow: hidden; }
    .progress-bar-fill { height: 100%; border-radius: 10px; }
</style>

<!-- Hero Welcome Banner -->
<div class="hero-banner">
    <div class="hero-text">
        <div style="font-size: 11px; font-weight: 700; letter-spacing: 2px; text-transform: uppercase; color: #9fa8da; margin-bottom: 5px;">Panel Kontrol Administrator</div>
        <h1>Selamat Datang, {{ Auth::user()->name }}! 👋</h1>
        <p>Sistem Informasi Pengarsipan Digital Kementerian berjalan normal. Kelola repositori dokumen dan otorisasi akses pegawai secara terpusat.</p>
    </div>
    <a href="{{ route('admin.arsip.create') }}" class="hero-btn">+ Upload Arsip Baru</a>
</div>

<!-- Grid 4 Kartu Statistik -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-info">
            <div class="label">Total Seluruh Arsip</div>
            <div class="number">{{ $totalArsip }}</div>
        </div>
        <div class="stat-icon icon-blue">📁</div>
    </div>

    <div class="stat-card">
        <div class="stat-info">
            <div class="label">Surat Masuk</div>
            <div class="number">{{ $totalSuratMasuk }}</div>
        </div>
        <div class="stat-icon icon-green">📥</div>
    </div>

    <div class="stat-card">
        <div class="stat-info">
            <div class="label">Surat Keluar</div>
            <div class="number">{{ $totalSuratKeluar }}</div>
        </div>
        <div class="stat-icon icon-orange">📤</div>
    </div>

    <div class="stat-card">
        <div class="stat-info">
            <div class="label">Pegawai Terdaftar</div>
            <div class="number">{{ $totalPegawai }}</div>
        </div>
        <div class="stat-icon icon-purple">👥</div>
    </div>
</div>

<!-- Dashboard Layout (Tabel Terbaru & Visualisasi Kategori) -->
<div class="dashboard-layout">
    
    <!-- Kolom Kiri: Tabel 5 Arsip Terbaru -->
    <div class="panel-card">
        <div class="panel-header">
            <h3>📑 Dokumen Arsip Terbaru Masuk</h3>
            <a href="{{ route('admin.arsip.index') }}">Lihat Semua &rarr;</a>
        </div>

        <table class="mini-table">
            <thead>
                <tr>
                    <th>No. Dokumen</th>
                    <th>Judul Arsip</th>
                    <th>Kategori</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($arsipTerbaru as $arsip)
                    <tr>
                        <td class="doc-num">{{ $arsip->nomor_dokumen }}</td>
                        <td>{{ Str::limit($arsip->judul, 30) }}</td>
                        <td>
                            <span class="badge {{ $arsip->kategori == 'Surat Masuk' ? 'badge-surat' : ($arsip->kategori == 'Surat Keputusan (SK)' ? 'badge-sk' : 'badge-laporan') }}">
                                {{ $arsip->kategori }}
                            </span>
                        </td>
                        <td style="color: var(--muted);">{{ \Carbon\Carbon::parse($arsip->tanggal_dokumen)->format('d M Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align: center; color: var(--muted); padding: 30px;">
                            Belum ada arsip yang diunggah ke sistem.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Kolom Kanan: Distribusi Kategori Dokumen -->
    <div class="panel-card">
        <div class="panel-header">
            <h3>📊 Distribusi Kategori</h3>
        </div>

        @php
            $denom = $totalArsip > 0 ? $totalArsip : 1;
            $pctMasuk = round(($totalSuratMasuk / $denom) * 100);
            $pctKeluar = round(($totalSuratKeluar / $denom) * 100);
            $pctSK = round(($totalSK / $denom) * 100);
            $pctLaporan = round(($totalLaporan / $denom) * 100);
        @endphp

        <div class="cat-progress-item">
            <div class="cat-progress-label">
                <span>Surat Masuk</span>
                <span>{{ $totalSuratMasuk }} ({{ $pctMasuk }}%)</span>
            </div>
            <div class="progress-bar-bg">
                <div class="progress-bar-fill" style="width: {{ $pctMasuk }}%; background: #176b49;"></div>
            </div>
        </div>

        <div class="cat-progress-item">
            <div class="cat-progress-label">
                <span>Surat Keluar</span>
                <span>{{ $totalSuratKeluar }} ({{ $pctKeluar }}%)</span>
            </div>
            <div class="progress-bar-bg">
                <div class="progress-bar-fill" style="width: {{ $pctKeluar }}%; background: #d97706;"></div>
            </div>
        </div>

        <div class="cat-progress-item">
            <div class="cat-progress-label">
                <span>Surat Keputusan (SK)</span>
                <span>{{ $totalSK }} ({{ $pctSK }}%)</span>
            </div>
            <div class="progress-bar-bg">
                <div class="progress-bar-fill" style="width: {{ $pctSK }}%; background: #6d28d9;"></div>
            </div>
        </div>

        <div class="cat-progress-item">
            <div class="cat-progress-label">
                <span>Laporan Kegiatan</span>
                <span>{{ $totalLaporan }} ({{ $pctLaporan }}%)</span>
            </div>
            <div class="progress-bar-bg">
                <div class="progress-bar-fill" style="width: {{ $pctLaporan }}%; background: #245c91;"></div>
            </div>
        </div>

    </div>

</div>
@endsection