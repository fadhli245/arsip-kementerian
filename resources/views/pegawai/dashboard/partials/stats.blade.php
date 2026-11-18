<section class="pegawai-stats" aria-label="Statistik aktivitas pegawai">
    <article class="stat-card stat-card--blue">
        <div class="stat-top"><span class="stat-label">ARSIP TERSEDIA</span><span class="stat-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3.5 7.5A2.5 2.5 0 0 1 6 5h4l2 2h6a2.5 2.5 0 0 1 2.5 2.5v8A2.5 2.5 0 0 1 18 20H6a2.5 2.5 0 0 1-2.5-2.5v-10Z"/></svg></span></div>
        <strong class="stat-number" x-init="animateCount($el, {{ $totalArsip }})">{{ number_format($totalArsip, 0, ',', '.') }}</strong>
        <span class="stat-trend {{ $arsipBaruMingguIni > 0 ? 'trend-positive' : 'trend-muted' }}">{{ $arsipBaruMingguIni > 0 ? '+' . number_format($arsipBaruMingguIni, 0, ',', '.') . ' dokumen baru minggu ini' : 'Tidak ada dokumen baru minggu ini' }}</span>
    </article>
    <article class="stat-card stat-card--indigo">
        <div class="stat-top"><span class="stat-label">TOTAL AKSES</span><span class="stat-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 3v18h18M7 14l4-4 3 3 6-7"/><path d="M16 6h4v4"/></svg></span></div>
        <strong class="stat-number" x-init="animateCount($el, {{ $totalAkses }})">{{ number_format($totalAkses, 0, ',', '.') }}</strong>
        @if($aksesPersenVsBulanLalu === null)
            <span class="stat-trend trend-muted">Belum ada pembanding</span>
        @else
            <span class="stat-trend {{ $aksesPersenVsBulanLalu >= 0 ? 'trend-positive' : 'trend-negative' }}">{{ $aksesPersenVsBulanLalu >= 0 ? '↑' : '↓' }} {{ abs($aksesPersenVsBulanLalu) }}% dibanding bulan lalu</span>
        @endif
    </article>
    <article class="stat-card stat-card--green">
        <div class="stat-top"><span class="stat-label">UNDUHAN</span><span class="stat-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><path d="m7 10 5 5 5-5M12 15V3"/></svg></span></div>
        <strong class="stat-number" x-init="animateCount($el, {{ $totalUnduhan }})">{{ number_format($totalUnduhan, 0, ',', '.') }}</strong>
        <span class="stat-trend trend-muted">{{ number_format($unduhanBulanIni, 0, ',', '.') }} unduhan bulan ini</span>
    </article>
    <article class="stat-card stat-card--purple">
        <div class="stat-top"><span class="stat-label">PREVIEW</span><span class="stat-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7S2 12 2 12Z"/><circle cx="12" cy="12" r="3"/></svg></span></div>
        <strong class="stat-number" x-init="animateCount($el, {{ $totalPreview }})">{{ number_format($totalPreview, 0, ',', '.') }}</strong>
        <span class="stat-trend trend-muted">{{ number_format($previewBulanIni, 0, ',', '.') }} preview bulan ini</span>
    </article>
</section>
