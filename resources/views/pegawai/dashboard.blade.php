@extends('layouts.app')

@section('title', 'Dashboard Pegawai - SIM Arsip Kementerian Perhubungan Laut')

@section('content')
<style>
    .pegawai-dashboard { display: flex; flex-direction: column; gap: 22px; }
    .pegawai-hero { padding: 28px 32px; color: #fff; border-radius: 16px; background: linear-gradient(135deg, #0A192F, #0284C7); }
    .pegawai-hero h1 { font-size: 24px; margin: 6px 0 8px; }
    .pegawai-hero p { color: #E2E8F0; max-width: 720px; line-height: 1.6; }
    .pegawai-stats { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 14px; }
    .pegawai-card, .pegawai-panel { background: #fff; border: 1px solid #E2E8F0; border-radius: 14px; box-shadow: 0 4px 12px rgba(15,23,42,.03); }
    .pegawai-card { padding: 18px 20px; }
    .pegawai-card small { color: #64748B; font-weight: 700; text-transform: uppercase; letter-spacing: .4px; }
    .pegawai-card strong { display: block; color: #0F172A; font-size: 27px; margin-top: 8px; }
    .pegawai-panel { padding: 20px; }
    .pegawai-panel h2 { color: #0F172A; font-size: 16px; margin: 0 0 14px; }
    .pegawai-grid { display: grid; grid-template-columns: 1.2fr 1fr; gap: 18px; }
    .pegawai-table-wrap { overflow-x: auto; }
    .pegawai-table { width: 100%; border-collapse: collapse; font-size: 13px; }
    .pegawai-table th, .pegawai-table td { padding: 12px 10px; text-align: left; border-bottom: 1px solid #E2E8F0; vertical-align: middle; }
    .pegawai-table th { color: #475569; background: #F8FAFC; white-space: nowrap; }
    .pegawai-table td { color: #334155; }
    .category-filters { display: flex; flex-wrap: wrap; gap: 8px; margin: 0 0 14px; }
    .category-chip { text-decoration: none; padding: 7px 11px; border-radius: 20px; border: 1px solid #CBD5E1; color: #475569; font-size: 12px; font-weight: 700; }
    .category-chip.active, .category-chip:hover { background: #E0F2FE; color: #0369A1; border-color: #7DD3FC; }
    .action-icon { width: 34px; height: 34px; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px; border: 1px solid #CBD5E1; color: #0369A1; background: #fff; text-decoration: none; }
    .action-icon:hover { background: #E0F2FE; }
    .action-icon.download { color: #15803D; border-color: #BBF7D0; background: #F0FDF4; }
    .status { display: inline-block; padding: 5px 9px; border-radius: 20px; background: #FEF3C7; color: #92400E; font-size: 11px; font-weight: 700; }
    .request-form { display: grid; gap: 10px; }
    .request-form input, .request-form textarea { width: 100%; padding: 10px 12px; border: 1px solid #CBD5E1; border-radius: 8px; font: inherit; }
    .request-form button { justify-self: start; background: #0369A1; color: #fff; border: 0; border-radius: 8px; padding: 10px 15px; font-weight: 700; cursor: pointer; }
    .empty-state { color: #64748B; padding: 20px 10px !important; text-align: center !important; }
    @media (max-width: 800px) { .pegawai-stats { grid-template-columns: repeat(2, minmax(0,1fr)); } .pegawai-grid { grid-template-columns: 1fr; } }
    @media (max-width: 480px) { .pegawai-stats { grid-template-columns: 1fr 1fr; gap: 8px; } .pegawai-card { padding: 14px; } .pegawai-hero { padding: 22px; } }
</style>

<div class="pegawai-dashboard">
    <section class="pegawai-hero">
        <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:1.5px;color:#BAE6FD">Portal Layanan Pegawai</div>
        <h1>Selamat datang, {{ auth()->user()->name }}!</h1>
        <p>Akses arsip kedinasan yang tersedia, pantau aktivitas Anda, dan ajukan permohonan dokumen melalui dashboard ini.</p>
    </section>

    <section class="pegawai-stats" aria-label="Statistik aktivitas pegawai">
        <div class="pegawai-card"><small>Arsip tersedia</small><strong>{{ number_format($totalArsip) }}</strong></div>
        <div class="pegawai-card"><small>Total akses</small><strong>{{ number_format($totalAkses) }}</strong></div>
        <div class="pegawai-card"><small>Unduhan</small><strong>{{ number_format($totalUnduhan) }}</strong></div>
        <div class="pegawai-card"><small>Preview</small><strong>{{ number_format($totalPreview) }}</strong></div>
    </section>

    <section class="pegawai-panel">
        <h2>Arsip Kedinasan Terbaru &amp; Siap Unduh</h2>
        <nav class="category-filters" aria-label="Filter cepat kategori arsip">
            <a class="category-chip {{ $kategoriAktif === '' ? 'active' : '' }}" href="{{ route('pegawai.dashboard') }}">Semua</a>
            @foreach($kategoriList as $kategori)
                <a class="category-chip {{ $kategoriAktif === $kategori ? 'active' : '' }}" href="{{ route('pegawai.dashboard', ['kategori' => $kategori]) }}">{{ $kategori }}</a>
            @endforeach
            <a class="category-chip" href="{{ route('pegawai.arsip.index', ['kategori' => $kategoriAktif ?: null]) }}">Pencarian lanjutan</a>
        </nav>
        <div class="pegawai-table-wrap">
            <table class="pegawai-table">
                <thead><tr><th>Judul Dokumen</th><th>Kategori</th><th>Tanggal Dokumen</th><th style="text-align:center">Aksi</th></tr></thead>
                <tbody>
                @forelse($arsips as $arsip)
                    <tr>
                        <td><strong>{{ $arsip->judul }}</strong></td>
                        <td>{{ $arsip->kategori }}</td>
                        <td>{{ $arsip->tanggal_dokumen ? \Carbon\Carbon::parse($arsip->tanggal_dokumen)->format('d/m/Y') : '-' }}</td>
                        <td style="text-align:center;white-space:nowrap">
                            <a class="action-icon" href="{{ route('pegawai.arsip.preview', $arsip->id) }}" target="_blank" rel="noopener" aria-label="Preview {{ $arsip->judul }}" title="Preview" >
                                <svg aria-hidden="true" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7S2 12 2 12Z"/><circle cx="12" cy="12" r="3"/></svg>
                            </a>
                            <a class="action-icon download" href="{{ route('pegawai.arsip.download', $arsip->id) }}" aria-label="Unduh {{ $arsip->judul }}" title="Unduh">
                                <svg aria-hidden="true" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><path d="m7 10 5 5 5-5M12 15V3"/></svg>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr><td class="empty-state" colspan="4">Belum ada arsip pada kategori ini.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <div class="pegawai-grid">
        <section class="pegawai-panel">
            <h2>Riwayat Unduhan / Akses Terakhir</h2>
            <div class="pegawai-table-wrap"><table class="pegawai-table">
                <thead><tr><th>Aktivitas</th><th>Dokumen</th><th>Waktu</th></tr></thead>
                <tbody>
                @forelse($riwayat as $log)
                    <tr><td>{{ $log->activity }}</td><td>{{ $log->subject->judul ?? $log->description }}</td><td>{{ $log->created_at->format('d/m/Y H:i') }}</td></tr>
                @empty
                    <tr><td colspan="3" class="empty-state">Belum ada aktivitas preview atau unduh.</td></tr>
                @endforelse
                </tbody>
            </table></div>
        </section>

        <section class="pegawai-panel">
            <h2>Status Pengajuan / Permohonan Arsip <span style="color:#64748B;font-size:12px">({{ $totalPermohonan }})</span></h2>
            <div class="pegawai-table-wrap"><table class="pegawai-table">
                <thead><tr><th>Permohonan</th><th>Status</th><th>Diajukan</th></tr></thead>
                <tbody>
                @forelse($permohonan as $item)
                    <tr><td>{{ $item->judul_arsip }}</td><td><span class="status" style="{{ $item->status === 'Disetujui' ? 'background:#DCFCE7;color:#166534' : ($item->status === 'Ditolak' ? 'background:#FEE2E2;color:#991B1B' : '') }}">{{ $item->status }}</span></td><td>{{ $item->created_at->format('d/m/Y') }}</td></tr>
                @empty
                    <tr><td colspan="3" class="empty-state">Belum ada permohonan arsip.</td></tr>
                @endforelse
                </tbody>
            </table></div>
            <form class="request-form" action="{{ route('pegawai.permohonan.store') }}" method="POST" style="margin-top:16px">
                @csrf
                <input type="text" name="judul_arsip" value="{{ old('judul_arsip') }}" maxlength="255" required placeholder="Judul arsip yang dibutuhkan">
                <textarea name="keterangan" rows="2" maxlength="2000" placeholder="Keterangan atau alasan permohonan (opsional)">{{ old('keterangan') }}</textarea>
                <button type="submit">Ajukan Permohonan</button>
            </form>
        </section>
    </div>
</div>
@endsection
