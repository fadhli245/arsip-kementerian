<section class="pegawai-hero" aria-labelledby="employee-welcome">
    <svg class="hero-decoration" viewBox="0 0 520 260" aria-hidden="true"><circle cx="360" cy="120" r="92"/><circle cx="360" cy="120" r="132"/><circle cx="360" cy="120" r="172"/><path d="M182 206c78-62 130-55 199-88 45-22 71-63 139-73"/></svg>
    <div class="hero-copy">
        <span class="hero-eyebrow"><i></i> PORTAL LAYANAN PEGAWAI</span>
        <h1 id="employee-welcome"><span x-text="greeting">{{ $greeting }}</span>, {{ $pegawai->name }}!</h1>
        <p>Akses arsip kedinasan, pantau aktivitas, dan ajukan permohonan dokumen Anda dalam satu tempat.</p>
        <div class="hero-meta">
            <span><svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M16 3v4M8 3v4M3 10h18"/></svg><span x-text="today">{{ $todayText }}</span></span>
            <span class="hero-meta-divider"></span>
            <span><svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>Terakhir login: {{ $pegawai->last_login_at ? $pegawai->last_login_at->timezone(config('app.timezone'))->format('d M Y, H:i') . ' WIB' : 'Login pertama' }}</span>
        </div>
    </div>
    <form class="hero-search" action="{{ route('pegawai.arsip.index') }}" method="GET" x-on:submit.prevent="submitSearch($event)">
        <label class="sr-only" for="dashboard-search">Cari arsip</label>
        <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="10.8" cy="10.8" r="6.8"/><path d="m16 16 5 5"/></svg>
        <input id="dashboard-search" type="search" name="q" minlength="2" maxlength="100" placeholder="Cari nomor surat, judul dokumen, atau kata kunci arsip..." x-ref="searchInput" x-model.trim="searchQuery" autocomplete="off">
        <button type="submit">Cari</button>
    </form>
    <p class="search-hint" x-show="searchError" x-cloak x-text="searchError" role="alert"></p>
    <div class="quick-searches" aria-label="Pencarian cepat">
        <span>Cari cepat:</span>
        @foreach($kategoriList->take(4) as $kategori)
            <button type="button" x-on:click="quickSearch(@js($kategori))">{{ $kategori }}</button>
        @endforeach
    </div>
</section>
