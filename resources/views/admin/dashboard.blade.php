@extends('layouts.app')

@section('title', 'Dashboard Admin - SIM Arsip Kementerian Perhubungan Laut')

@section('content')
<style>
    .admin-dashboard { display:flex; flex-direction:column; gap:20px; }
    .admin-hero { display:flex; justify-content:space-between; align-items:center; gap:20px; flex-wrap:wrap; padding:28px 32px; border-radius:16px; color:#fff; background:linear-gradient(120deg,#0A192F,#0A4C78 70%,#0284C7); box-shadow:0 10px 25px rgba(10,37,88,.12); }
    .admin-hero small { color:#BAE6FD; text-transform:uppercase; letter-spacing:1.4px; font-weight:700; }
    .admin-hero h1 { margin:7px 0; font-size:25px; }
    .admin-hero p { margin:0; color:#E2E8F0; }
    .admin-actions { display:flex; gap:9px; flex-wrap:wrap; }
    .admin-btn { display:inline-flex; align-items:center; justify-content:center; gap:7px; padding:10px 14px; border-radius:8px; text-decoration:none; font-weight:700; font-size:12px; }
    .admin-btn-primary { background:#F2C94C; color:#0A2558; }
    .admin-btn-light { color:#fff; border:1px solid rgba(255,255,255,.4); }
    .admin-stats { display:grid; grid-template-columns:repeat(4,minmax(0,1fr)); gap:14px; }
    .admin-stat,.admin-panel { background:#fff; border:1px solid #E2E8F0; border-radius:13px; box-shadow:0 4px 12px rgba(15,23,42,.025); }
    .admin-stat { padding:18px 20px; border-top:3px solid #0284C7; }
    .admin-stat small { color:#64748B; font-weight:700; font-size:11px; text-transform:uppercase; letter-spacing:.4px; }
    .admin-stat strong { display:block; margin:7px 0 3px; color:#0F172A; font-size:27px; }
    .admin-stat span { color:#64748B; font-size:11px; }
    .admin-panel { padding:19px 20px; }
    .admin-panel h2 { font-size:15px; margin:0 0 14px; color:#0F172A; }
    .admin-grid { display:grid; grid-template-columns:1.2fr 1fr; gap:16px; }
    .admin-table-wrap { overflow:auto; }
    .admin-table { width:100%; border-collapse:collapse; font-size:12px; }
    .admin-table th,.admin-table td { text-align:left; padding:10px 9px; border-bottom:1px solid #E2E8F0; vertical-align:middle; }
    .admin-table th { background:#F8FAFC; color:#475569; white-space:nowrap; }
    .admin-table td { color:#334155; }
    .admin-table a { color:#0369A1; font-weight:700; text-decoration:none; }
    .admin-empty { text-align:center!important; color:#94A3B8!important; padding:22px!important; }
    .chart { display:flex; align-items:flex-end; gap:10px; height:155px; padding:10px 2px 0; border-bottom:1px solid #CBD5E1; }
    .chart-item { display:flex; flex:1; height:100%; flex-direction:column; align-items:center; justify-content:flex-end; gap:6px; color:#64748B; font-size:10px; }
    .chart-bar { width:min(30px,75%); min-height:3px; background:linear-gradient(180deg,#38BDF8,#0369A1); border-radius:6px 6px 0 0; }
    .chart-value { color:#0F172A; font-size:10px; font-weight:700; }
    .category-row { display:grid; grid-template-columns:minmax(85px,1fr) 2fr 30px; gap:8px; align-items:center; margin:12px 0; font-size:11px; color:#475569; }
    .category-track { background:#E2E8F0; border-radius:10px; height:8px; overflow:hidden; }
    .category-bar { height:100%; border-radius:10px; background:#0284C7; }
    .quick-links { display:grid; grid-template-columns:repeat(4,1fr); gap:10px; }
    .quick-link { padding:13px; border:1px solid #E2E8F0; border-radius:10px; color:#0A2558; text-decoration:none; font-size:12px; font-weight:700; background:#F8FAFC; }
    .quick-link:hover { border-color:#7DD3FC; background:#F0F9FF; }
    .quick-link span { display:block; font-size:19px; margin-bottom:7px; }
    .status-pill { display:inline-block; background:#FEF3C7; color:#92400E; padding:4px 8px; border-radius:20px; font-size:10px; font-weight:700; }
    @media(max-width:900px) { .admin-stats{grid-template-columns:repeat(2,minmax(0,1fr))}.admin-grid{grid-template-columns:1fr}.quick-links{grid-template-columns:repeat(2,1fr)} }
    @media(max-width:500px) { .admin-hero{padding:22px}.admin-stats{gap:8px}.admin-stat{padding:14px}.admin-actions{width:100%}.admin-btn{flex:1} }
</style>

<main class="admin-dashboard">
    <section class="admin-hero">
        <div>
            <small>Portal Administrator</small>
            <h1>Dashboard Pengelolaan Arsip</h1>
            <p>Ringkasan dokumen, aktivitas pengguna, dan permohonan yang perlu ditinjau.</p>
        </div>
        <div class="admin-actions">
            <a class="admin-btn admin-btn-primary" href="{{ route('admin.arsip.create') }}">＋ Tambah Arsip</a>
            <a class="admin-btn admin-btn-light" href="{{ route('admin.permohonan.index') }}">Tinjau Permohonan</a>
        </div>
    </section>

    <section class="admin-stats" aria-label="Ringkasan sistem">
        <article class="admin-stat"><small>Total dokumen</small><strong>{{ number_format($totalArsip) }}</strong><span>Arsip tersimpan</span></article>
        <article class="admin-stat" style="border-top-color:#16A34A"><small>Pengguna pegawai</small><strong>{{ number_format($totalPegawai) }}</strong><span>Akun non-admin</span></article>
        <article class="admin-stat" style="border-top-color:#7C3AED"><small>Total akses dokumen</small><strong>{{ number_format($totalAkses) }}</strong><span>Preview dan unduhan</span></article>
        <article class="admin-stat" style="border-top-color:#F59E0B"><small>Unduhan</small><strong>{{ number_format($totalUnduhan) }}</strong><span>Unduhan tercatat</span></article>
    </section>

    <section class="admin-panel">
        <h2>Akses Cepat</h2>
        <div class="quick-links">
            <a class="quick-link" href="{{ route('admin.arsip.index') }}"><span>🗂️</span>Manajemen Arsip</a>
            @if(in_array(auth()->user()->role, ['admin', 'super_admin'], true))
                <a class="quick-link" href="{{ route('admin.users.index') }}"><span>👥</span>Manajemen Pengguna</a>
            @endif
            <a class="quick-link" href="{{ route('admin.permohonan.index') }}"><span>✅</span>Persetujuan <span class="status-pill">{{ $permohonanMenunggu }} menunggu</span></a>
            <a class="quick-link" href="{{ route('admin.aktivitas') }}"><span>🕒</span>Audit Trail</a>
            @if(in_array(auth()->user()->role, ['admin', 'super_admin'], true))
                <a class="quick-link" href="{{ route('admin.kategori.index') }}"><span>🏷️</span>Master Kategori &amp; Klasifikasi</a>
            @endif
        </div>
    </section>

    <section class="admin-grid">
        <article class="admin-panel">
            <h2>Dokumen Diunggah per Bulan</h2>
            <div class="chart" role="img" aria-label="Grafik total dokumen diunggah selama enam bulan terakhir">
                @foreach($uploadsBulanan as $bulan)
                    <div class="chart-item" title="{{ $bulan['label'] }}: {{ $bulan['total'] }} dokumen">
                        <span class="chart-value">{{ $bulan['total'] }}</span>
                        <div class="chart-bar" style="height:{{ max(3, (int) round(($bulan['total'] / $maxUpload) * 100)) }}%"></div>
                        <span>{{ $bulan['label'] }}</span>
                    </div>
                @endforeach
            </div>
        </article>
        <article class="admin-panel">
            <h2>Kategori Paling Banyak Diakses</h2>
            @php($maxCategoryAccess = max(1, (int) ($categoryAccess->max('total') ?? 0)))
            @forelse($categoryAccess as $category)
                <div class="category-row">
                    <span>{{ $category->kategori }}</span>
                    <div class="category-track"><div class="category-bar" style="width:{{ round(($category->total / $maxCategoryAccess) * 100) }}%"></div></div>
                    <strong>{{ $category->total }}</strong>
                </div>
            @empty
                <p style="color:#64748B;font-size:12px">Belum ada data preview atau unduhan untuk dihitung.</p>
            @endforelse
        </article>
    </section>

    <section class="admin-grid">
        <article class="admin-panel">
            <h2>Aktivitas Terbaru <a style="float:right;font-size:11px" href="{{ route('admin.aktivitas') }}">Lihat semua →</a></h2>
            <div class="admin-table-wrap"><table class="admin-table">
                <thead><tr><th>Pengguna</th><th>Aktivitas</th><th>Waktu</th></tr></thead>
                <tbody>
                    @forelse($logsTerbaru as $log)
                        <tr><td>{{ $log->user->name }}</td><td>{{ $log->activity }}<br><span style="color:#64748B">{{ \Illuminate\Support\Str::limit($log->description, 55) }}</span></td><td>{{ $log->created_at->format('d/m H:i') }}</td></tr>
                    @empty
                        <tr><td class="admin-empty" colspan="3">Belum ada aktivitas tercatat.</td></tr>
                    @endforelse
                </tbody>
            </table></div>
        </article>
        <article class="admin-panel">
            <h2>Login Terakhir</h2>
            <div class="admin-table-wrap"><table class="admin-table">
                <thead><tr><th>Nama</th><th>Peran</th><th>Login</th></tr></thead>
                <tbody>
                    @forelse($penggunaTerakhirLogin as $pengguna)
                        <tr><td>{{ $pengguna->name }}</td><td>{{ ucfirst($pengguna->role) }}</td><td>{{ $pengguna->last_login_at?->format('d/m H:i') ?? 'Belum pernah' }}</td></tr>
                    @empty
                        <tr><td class="admin-empty" colspan="3">Belum ada pengguna.</td></tr>
                    @endforelse
                </tbody>
            </table></div>
        </article>
    </section>

    <section class="admin-panel">
        <h2>Arsip Terbaru <a style="float:right;font-size:11px" href="{{ route('admin.arsip.index') }}">Kelola semua →</a></h2>
        <div class="admin-table-wrap"><table class="admin-table">
            <thead><tr><th>Dokumen</th><th>Nomor Surat</th><th>Kategori</th><th>Pengunggah</th><th>Ditambahkan</th><th>Aksi</th></tr></thead>
            <tbody>
                @forelse($arsipTerbaru as $arsip)
                    <tr><td>{{ $arsip->judul }}</td><td>{{ $arsip->nomor_surat ?: '-' }}</td><td>{{ $arsip->kategori }}</td><td>{{ $arsip->user->name ?? '-' }}</td><td>{{ $arsip->created_at->format('d/m/Y') }}</td><td><a href="{{ route('admin.arsip.edit', $arsip->id) }}">Edit</a> · <a href="{{ route('admin.arsip.show', $arsip->id) }}">Detail</a></td></tr>
                @empty
                    <tr><td class="admin-empty" colspan="6">Belum ada arsip.</td></tr>
                @endforelse
            </tbody>
        </table></div>
    </section>
</main>
@endsection
