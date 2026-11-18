@extends('layouts.app')

@section('title', 'Dashboard Pegawai - SIM Arsip Kementerian Perhubungan Laut')

@push('styles')
    @vite(['resources/css/pegawai-dashboard.css', 'resources/js/pegawai-dashboard.js'])
@endpush

@section('content')
@php
    $hour = now()->hour;
    $greeting = $hour < 11 ? 'Selamat Pagi' : ($hour < 15 ? 'Selamat Siang' : ($hour < 18 ? 'Selamat Sore' : 'Selamat Malam'));
    $todayText = now()->locale('id')->translatedFormat('l, d F Y');
@endphp
<div class="pegawai-dashboard" x-data="pegawaiDashboard()">
    <script>
        window.pegawaiDashboardData = {
            archives: @js($arsipDashboardData),
            favorites: @js($favoriteData),
            history: @js($riwayatDashboardData),
            favoriteIds: @js($favoriteIds),
            distribution: @js($distribusiKategori),
            categories: @js($kategoriList->values()),
            categoryCounts: @js($jumlahKategori),
            csrf: @js(csrf_token()),
            selectedCategory: @js($kategoriAktif),
            selectedYear: @js($tahunAktif),
            notificationUrl: @js(route('pegawai.notifications.read-all')),
        };
    </script>

    @include('pegawai.dashboard.partials.hero', ['greeting' => $greeting, 'todayText' => $todayText])
    @include('pegawai.dashboard.partials.stats')

    <div class="dashboard-main-grid">
        <section class="dashboard-panel archive-panel" aria-labelledby="archive-panel-title">
            <div class="panel-heading archive-heading">
                <div>
                    <span class="section-kicker">KOLEKSI DOKUMEN</span>
                    <h2 id="archive-panel-title">Arsip Kedinasan Terbaru &amp; Siap Unduh</h2>
                    <p>Dokumen yang dapat Anda akses sesuai hak akses akun.</p>
                </div>
                <label class="year-filter">
                    <span>Tahun</span>
                    <select x-model="tahun" aria-label="Filter tahun arsip">
                        <option value="">Semua Tahun</option>
                        @foreach($daftarTahun as $tahun)
                            <option value="{{ $tahun }}" {{ $tahunAktif === (string) $tahun ? 'selected' : '' }}>{{ $tahun }}</option>
                        @endforeach
                    </select>
                </label>
            </div>

            <div class="archive-filters" role="group" aria-label="Filter kategori arsip">
                <button type="button" class="filter-pill" :class="{ 'is-active': kategori === '' }" x-on:click="kategori = ''">Semua <span>{{ $totalArsip }}</span></button>
                @foreach($kategoriList as $kategori)
                    <button type="button" class="filter-pill" :class="{ 'is-active': kategori === @js($kategori) }" x-on:click="kategori = @js($kategori)">
                        {{ $kategori }} <span>{{ $jumlahKategori->get($kategori, 0) }}</span>
                    </button>
                @endforeach
                <a class="filter-pill filter-advanced" href="{{ route('pegawai.arsip.index') }}">Pencarian lanjutan <span aria-hidden="true">↗</span></a>
            </div>

            <div class="table-scroll">
                <table class="archive-table">
                    <thead><tr><th>Judul Dokumen</th><th>Kategori</th><th>Tanggal</th><th>Akses</th><th class="actions-column">Aksi</th></tr></thead>
                    <tbody>
                    <template x-for="arsip in filteredArchives()" :key="arsip.id">
                        <tr>
                            <td class="archive-title-cell">
                                <button type="button" class="text-link archive-title" x-text="arsip.judul" x-on:click="openPreview(arsip, $event.currentTarget)" x-bind:title="arsip.judul"></button>
                                <span class="archive-meta" x-text="'No. ' + arsip.nomor + ' · ' + arsip.format + ' (' + arsip.ukuran + ')'"></span>
                            </td>
                            <td><span class="category-badge" x-bind:class="categoryClass(arsip.kategori)" x-text="arsip.kategori"></span></td>
                            <td class="date-cell" x-text="arsip.tanggal"></td>
                            <td><span class="access-badge" x-bind:class="arsip.akses === 'Terbatas' || arsip.akses === 'Rahasia' ? 'access-restricted' : ''"><svg x-show="arsip.akses === 'Terbatas' || arsip.akses === 'Rahasia'" viewBox="0 0 24 24" aria-hidden="true"><rect x="5" y="10" width="14" height="10" rx="2"/><path d="M8 10V7a4 4 0 1 1 8 0v3"/></svg><span x-text="arsip.akses"></span></span></td>
                            <td class="row-actions">
                                <button class="icon-button favorite-action" type="button" x-bind:class="{ 'is-favorite': favoriteIds.includes(arsip.id) }" x-bind:aria-pressed="favoriteIds.includes(arsip.id)" x-bind:aria-label="favoriteIds.includes(arsip.id) ? 'Hapus dari favorit' : 'Tambahkan ke favorit'" title="Favorit" x-on:click="toggleFavorite(arsip)"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="m12 3 2.8 5.7 6.2.9-4.5 4.4 1.1 6.2-5.6-3-5.6 3 1.1-6.2L3 9.6l6.2-.9L12 3Z"/></svg></button>
                                <button class="icon-button preview-action" type="button" x-bind:aria-label="'Pratinjau ' + arsip.judul" title="Pratinjau" x-on:click="openPreview(arsip, $event.currentTarget)"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7S2 12 2 12Z"/><circle cx="12" cy="12" r="3"/></svg></button>
                                <a class="icon-button download-action" x-bind:href="arsip.downloadUrl" x-bind:aria-label="'Unduh ' + arsip.judul" title="Unduh"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><path d="m7 10 5 5 5-5M12 15V3"/></svg></a>
                            </td>
                        </tr>
                    </template>
                    <tr x-show="filteredArchives().length === 0"><td class="table-empty" colspan="5">Tidak ada arsip untuk filter ini. <button type="button" class="text-link" x-on:click="kategori = ''; tahun = ''">Reset filter</button></td></tr>
                    </tbody>
                </table>
                @if($arsips->isEmpty())
                    <div class="empty-card-state"><span class="empty-icon">▤</span><strong>Belum ada arsip yang tersedia</strong><p>Dokumen yang dapat Anda akses akan muncul di sini.</p></div>
                @endif
            </div>
            @if($arsips->hasPages())
                <div class="pagination-wrap">{{ $arsips->links() }}</div>
            @endif
        </section>

        <aside class="dashboard-side-widgets">
            @include('pegawai.dashboard.partials.chart')
            @include('pegawai.dashboard.partials.favorites')
        </aside>
    </div>

    <div class="dashboard-bottom-grid">
        @include('pegawai.dashboard.partials.activity')
        @include('pegawai.dashboard.partials.requests')
    </div>

    @include('pegawai.dashboard.partials.preview-modal')

    <div class="dashboard-toast" role="status" aria-live="polite" x-show="toast.visible" x-transition x-cloak x-text="toast.message"></div>
</div>
@endsection
