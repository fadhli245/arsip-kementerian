@extends('layouts.app')

@section('title', 'Dashboard Admin - SIM Arsip Kementerian Perhubungan Laut')

@push('styles')
    @vite(['resources/css/admin-dashboard.css', 'resources/js/admin-dashboard.js'])
@endpush

@section('content')
<main class="admin-dashboard" x-data="adminDashboard()">
    <script>
        window.adminDashboardData = {
            chartUrl: @js(route('admin.dashboard.chart')),
            years: @js($daftarTahun),
            selectedYear: @js($tahunAktif),
            chart: @js($grafik),
            csrf: @js(csrf_token()),
            notificationUrl: @js(route('admin.notifications.read-all')),
            unread: @js($notifikasiBelumDibaca),
            storagePercent: @js($statusSistem['storage_persen']),
        };
    </script>

    {{-- Header dashboard --}}
    <header class="admin-dashboard-heading">
        <div><span class="dashboard-kicker">DIREKTORAT JENDERAL PERHUBUNGAN LAUT</span><h1>Dashboard Pengelolaan Arsip</h1><p>Ringkasan arsip, permohonan, dan aktivitas sistem.</p></div>
        <div class="admin-heading-actions">
            <div class="admin-notification" x-data="notificationMenu()" data-unread="{{ $notifikasiBelumDibaca }}">
                <button class="dashboard-icon-button" type="button" aria-label="Notifikasi" x-on:click="open = !open" x-bind:aria-expanded="open">
                    <span aria-hidden="true">♧</span><span class="notification-count" x-show="unread > 0" x-cloak x-text="unread > 9 ? '9+' : unread"></span>
                </button>
                <div class="admin-notification-menu" x-show="open" x-transition x-cloak x-on:click.outside="open = false" x-on:keydown.escape.window="open = false">
                    <div class="notification-menu-heading"><strong>Notifikasi</strong><span x-text="unread + ' belum dibaca'"></span></div>
                    @forelse($daftarNotifikasi as $notification)
                        <a class="admin-notification-item {{ $notification->read_at ? 'is-read' : '' }}" href="{{ $notification->url ?: route('admin.permohonan.index') }}">
                            <span class="notification-dot" aria-hidden="true">{{ $notification->type === 'success' ? '✓' : '•' }}</span>
                            <span><strong>{{ $notification->title }}</strong><small>{{ $notification->message }}</small><time>{{ $notification->created_at->locale('id')->diffForHumans() }}</time></span>
                            @if(!$notification->read_at)<i aria-label="Belum dibaca"></i>@endif
                        </a>
                    @empty
                        <p class="admin-empty-state">Tidak ada notifikasi baru.</p>
                    @endforelse
                    <button class="mark-read-button" type="button" x-on:click="markAllRead()" x-bind:disabled="unread === 0">Tandai semua dibaca</button>
                </div>
            </div>
            <div class="admin-profile-chip"><span class="avatar-glass">{{ strtoupper(mb_substr(auth()->user()->name ?? 'A', 0, 1)) }}</span><span><strong>{{ auth()->user()->name ?? 'Administrator' }}</strong><small>{{ auth()->user()->division ?: 'Administrator' }}</small></span></div>
        </div>
    </header>

    {{-- Banner utama --}}
    <section class="admin-hero">
        <div class="hero-copy"><span class="hero-label">PORTAL ADMINISTRATOR</span><h2>{{ $greeting }}, {{ auth()->user()->name ?? 'Administrator' }}!</h2><p>Kelola arsip dan tindak lanjuti kebutuhan layanan dalam satu tempat.</p>
            <div class="hero-status"><span class="system-indicator {{ $statusSistem['healthy'] ? 'is-healthy' : 'is-warning' }}"></span><span>{{ $statusSistem['pesan'] }}</span><span class="hero-separator">•</span><a href="{{ route('admin.permohonan.index') }}">{{ $permohonanMenunggu }} permohonan menunggu tinjauan</a></div>
            <small class="hero-date">{{ now()->locale('id')->translatedFormat('l, d F Y') }}</small>
        </div>
        <div class="admin-actions"><a class="dashboard-button button-gold" href="{{ route('admin.arsip.create') }}">＋ Tambah Arsip</a><a class="dashboard-button button-outline" href="{{ route('admin.permohonan.index') }}">Kelola Permohonan</a></div>
    </section>

    {{-- Statistik ringkas --}}
    <section class="admin-stats" aria-label="Ringkasan sistem">
        <article class="admin-stat"><span class="stat-icon icon-blue" aria-hidden="true">▣</span><span class="stat-label">Total Arsip Tersimpan</span><strong>{{ number_format($totalArsip, 0, ',', '.') }}</strong><small>{{ number_format($arsipBulanIni, 0, ',', '.') }} ditambahkan bulan ini</small></article>
        <a class="admin-stat stat-pending {{ $permohonanMenunggu ? 'needs-action' : '' }}" href="{{ route('admin.permohonan.index') }}"><span class="stat-icon icon-amber" aria-hidden="true">◷</span><span class="stat-label">Permohonan Pending</span><strong>{{ number_format($permohonanMenunggu, 0, ',', '.') }}</strong><small>{{ $permohonanMenunggu ? 'Perlu ditinjau' : 'Semua permohonan sudah diproses' }}</small></a>
        <article class="admin-stat"><span class="stat-icon icon-green" aria-hidden="true">♧</span><span class="stat-label">Pengguna Aktif</span><strong>{{ number_format($totalPenggunaAktif, 0, ',', '.') }}</strong><small>{{ $adminCount }} Admin · {{ $pegawaiCount }} Pegawai</small></article>
        <article class="admin-stat"><span class="stat-icon icon-purple" aria-hidden="true">▤</span><span class="stat-label">Kapasitas Penyimpanan</span><strong>{{ $storageUsedHuman }} <span class="stat-total">/ {{ $storageQuotaHuman }}</span></strong><div class="storage-track"><span style="width: {{ min(100, $statusSistem['storage_persen']) }}%; background: {{ $statusSistem['storage_persen'] > 90 ? '#dc2626' : ($statusSistem['storage_persen'] >= 70 ? '#d97706' : '#16a34a') }}"></span></div><small>{{ $statusSistem['ukuran_tidak_diketahui'] ? 'Ukuran belum tercatat untuk ' . $statusSistem['ukuran_tidak_diketahui'] . ' arsip' : $statusSistem['storage_persen'] . '% terpakai' }}</small></article>
    </section>

    {{-- Akses cepat --}}
    <section class="dashboard-panel">
        <div class="panel-heading"><div><span class="section-kicker">NAVIGASI</span><h2>Akses Cepat</h2></div></div>
        <div class="quick-links">
            <a class="quick-link" href="{{ route('admin.arsip.index') }}"><span class="quick-icon icon-blue">▣</span><strong>Manajemen Arsip</strong><small>Unggah dan kelola metadata dokumen</small><span class="quick-arrow">→</span></a>
            <a class="quick-link" href="{{ route('admin.permohonan.index') }}"><span class="quick-icon icon-amber">◷</span><strong>Persetujuan Permohonan</strong><small>Tinjau permohonan arsip pegawai</small>@if($permohonanMenunggu)<b class="pending-badge">{{ $permohonanMenunggu }} menunggu</b>@endif<span class="quick-arrow">→</span></a>
            @if(in_array(auth()->user()->role, ['admin', 'super_admin'], true))
                <a class="quick-link" href="{{ route('admin.users.index') }}"><span class="quick-icon icon-green">♧</span><strong>Manajemen Pengguna</strong><small>Atur role dan akses pengguna</small><span class="quick-arrow">→</span></a>
                <a class="quick-link" href="{{ route('admin.kategori.index') }}"><span class="quick-icon icon-purple">▤</span><strong>Kategori &amp; Klasifikasi</strong><small>Atur jenis surat dan klasifikasi</small><span class="quick-arrow">→</span></a>
            @else
                <a class="quick-link" href="{{ route('admin.aktivitas') }}"><span class="quick-icon icon-green">◷</span><strong>Riwayat Aktivitas</strong><small>Pantau tindakan pengguna</small><span class="quick-arrow">→</span></a>
                <a class="quick-link" href="{{ route('admin.arsip.create') }}"><span class="quick-icon icon-purple">＋</span><strong>Tambah Arsip</strong><small>Tambahkan dokumen baru</small><span class="quick-arrow">→</span></a>
            @endif
        </div>
    </section>

    {{-- Grafik dan antrean --}}
    <section class="dashboard-content-grid">
        <article class="dashboard-panel chart-panel">
            <div class="panel-heading"><div><span class="section-kicker">ANALITIK</span><h2>Dokumen Diunggah per Bulan</h2></div><label class="year-filter">Tahun <select x-model="year" x-on:change="loadChart()">@foreach($daftarTahun as $tahun)<option value="{{ $tahun }}">{{ $tahun }}</option>@endforeach</select></label></div>
            <div class="chart-summary"><span>Total tahun ini <strong x-text="chartTotal"></strong></span><span>Bulan tertinggi <strong x-text="peakMonth"></strong></span></div>
            <div class="chart-loading" x-show="chartLoading" x-cloak>Memuat grafik…</div><div class="chart-error" x-show="chartError" x-cloak><span x-text="chartError"></span><button type="button" x-on:click="loadChart()">Coba lagi</button></div>
            <div class="chart-empty" x-show="!chartLoading && !chartError && chartTotal === 0" x-cloak>Belum ada arsip yang diunggah pada tahun ini.</div>
            <div class="admin-chart-wrap" x-show="!chartLoading && !chartError && chartTotal > 0"><canvas id="adminUploadsChart" aria-label="Grafik jumlah arsip yang diunggah per bulan" role="img"></canvas></div>
            <table class="sr-only"><caption>Jumlah arsip diunggah per bulan</caption><tbody><template x-for="month in chart.labels"><tr><th x-text="month"></th><td x-text="chart.data[chart.labels.indexOf(month)]"></td></tr></template></tbody></table>
        </article>
        <div class="dashboard-stack">
            <article class="dashboard-panel pending-panel">
                <div class="panel-heading"><div><span class="section-kicker">TINDAKAN DIPERLUKAN</span><h2>Permohonan Terbaru</h2></div><a class="panel-link" href="{{ route('admin.permohonan.index') }}">Lihat semua →</a></div>
                @forelse($permohonanTerbaru as $request)
                    <div class="pending-item"><span class="avatar-glass avatar-small">{{ strtoupper(mb_substr($request->user->name ?? 'P', 0, 1)) }}</span><div class="pending-copy"><strong>{{ $request->user->name ?? 'Pengguna' }}</strong><small>{{ $request->user->division ?: 'Pegawai' }} · {{ $request->created_at->locale('id')->diffForHumans() }}</small><span>{{ $request->judul_arsip }}</span></div><div class="pending-actions"><form method="POST" action="{{ route('admin.permohonan.update', $request) }}">@csrf @method('PATCH')<input type="hidden" name="status" value="Disetujui"><button class="approval-button approve" aria-label="Setujui permohonan {{ $request->judul_arsip }}" title="Setujui">✓</button></form><details class="reject-details"><summary class="approval-button reject" aria-label="Tolak permohonan {{ $request->judul_arsip }}" title="Tolak">×</summary><form class="reject-form" method="POST" action="{{ route('admin.permohonan.update', $request) }}">@csrf @method('PATCH')<input type="hidden" name="status" value="Ditolak"><label>Alasan penolakan<textarea name="alasan_penolakan" required minlength="10" maxlength="300" rows="2"></textarea></label><button type="submit">Tolak Permohonan</button></form></details></div></div>
                @empty
                    <div class="admin-empty-state">✓ Tidak ada permohonan yang menunggu.</div>
                @endforelse
            </article>
            <article class="dashboard-panel categories-panel"><div class="panel-heading"><div><span class="section-kicker">DOKUMEN</span><h2>Kategori Paling Banyak Diakses</h2></div></div>
                @forelse($categoryAccess as $category)
                    @php($categoryPercent = $categoryMaxAccess ? (int) round($category->total / $categoryMaxAccess * 100) : 0)
                    <div class="category-row" title="{{ $category->kategori }}"><span>{{ $category->kategori }}</span><div class="category-track"><span style="width: {{ $categoryPercent }}%"></span></div><strong>{{ $category->total }}</strong></div>
                @empty <p class="admin-empty-state">Belum ada data akses dokumen.</p> @endforelse
            </article>
        </div>
    </section>

    {{-- Aktivitas dan pengguna --}}
    <section class="dashboard-content-grid equal-columns">
        <article class="dashboard-panel"><div class="panel-heading"><div><span class="section-kicker">AUDIT TRAIL</span><h2>Aktivitas Terbaru</h2></div><a class="panel-link" href="{{ route('admin.aktivitas') }}">Lihat semua log →</a></div><div class="activity-list">
            @forelse($logsTerbaru as $log)<div class="activity-item"><span class="avatar-glass avatar-small">{{ strtoupper(mb_substr($log->user->name ?? 'P', 0, 1)) }}</span><div><strong>{{ $log->user->name ?? 'Pengguna' }}</strong><span>{{ $log->activity }}</span><small title="{{ $log->created_at->format('d/m/Y H:i') }}">{{ $log->created_at->locale('id')->diffForHumans() }}</small></div><span class="activity-badge">{{ $log->activity }}</span></div>
            @empty <p class="admin-empty-state">Belum ada aktivitas tercatat.</p> @endforelse
        </div></article>
        <article class="dashboard-panel"><div class="panel-heading"><div><span class="section-kicker">PENGGUNA</span><h2>Login Terakhir</h2></div>@if(Route::has('admin.users.index'))<a class="panel-link" href="{{ route('admin.users.index') }}">Kelola pengguna →</a>@endif</div><div class="login-list">
            @forelse($penggunaTerakhirLogin as $pengguna)<div class="login-item"><span class="avatar-glass avatar-small">{{ strtoupper(mb_substr($pengguna->name ?? 'P', 0, 1)) }}</span><strong title="{{ $pengguna->name }}">{{ $pengguna->name }}</strong><span class="role-badge {{ in_array($pengguna->role, ['admin','super_admin','admin_divisi'], true) ? 'role-admin' : 'role-staff' }}">{{ in_array($pengguna->role, ['admin','super_admin','admin_divisi'], true) ? 'Admin' : 'Pegawai' }}</span><small>{{ $pengguna->last_login_at?->locale('id')->diffForHumans() ?? 'Belum pernah login' }}</small></div>
            @empty <p class="admin-empty-state">Belum ada data login.</p> @endforelse
        </div></article>
    </section>

    {{-- Arsip terbaru --}}
    <section class="dashboard-panel"><div class="panel-heading"><div><span class="section-kicker">KOLEKSI</span><h2>Arsip Terbaru</h2></div><a class="panel-link" href="{{ route('admin.arsip.index') }}">Kelola semua →</a></div>
        <div class="admin-table-wrap"><table class="admin-table"><thead><tr><th>Judul Dokumen</th><th>Nomor Surat</th><th>Kategori</th><th>Pengunggah</th><th>Tanggal</th><th>Aksi</th></tr></thead><tbody>
            @forelse($arsipTerbaru as $arsip)<tr><td><strong title="{{ $arsip->judul }}">{{ \Illuminate\Support\Str::limit($arsip->judul, 55) }}</strong></td><td>{{ $arsip->nomor_surat ?: '—' }}</td><td><span class="category-badge">{{ $arsip->kategori }}</span></td><td>{{ $arsip->user->name ?? '—' }}</td><td>{{ ($arsip->tanggal_unggah ?: $arsip->created_at)->format('d/m/Y') }}</td><td class="row-actions"><a href="{{ route('admin.arsip.show', $arsip) }}">Detail</a><a href="{{ route('admin.arsip.edit', $arsip) }}">Edit</a></td></tr>
            @empty <tr><td class="admin-empty-state" colspan="6">Belum ada arsip. <a href="{{ route('admin.arsip.create') }}">Tambah arsip</a></td></tr> @endforelse
        </tbody></table></div>
    </section>
    <div class="dashboard-toast" role="status" aria-live="polite" x-show="toast" x-transition x-cloak x-text="toast"></div>
</main>
@endsection
