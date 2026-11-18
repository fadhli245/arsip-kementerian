<section class="dashboard-panel widget-panel favorites-panel" aria-labelledby="favorites-title">
    <div class="panel-heading compact-heading">
        <div><span class="section-kicker">AKSES CEPAT</span><h2 id="favorites-title">Arsip Disematkan / Favorit</h2></div>
        <span class="count-badge" x-text="favoriteIds.length">{{ $favorit->count() }}</span>
    </div>
    <ul class="favorite-list" x-show="favoriteArchives.length > 0" x-cloak>
        <template x-for="arsip in favoriteArchives" :key="arsip.id">
            <li class="favorite-item" x-transition>
                <button type="button" class="favorite-file-icon" x-on:click="openPreview(arsip, $event.currentTarget)" x-bind:aria-label="'Pratinjau ' + arsip.judul"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6 3.5h8l4.5 4.6v12.4H6z"/><path d="M14 3.5v5h4.5M9 13h6M9 16h6"/></svg></button>
                <button type="button" class="favorite-copy" x-on:click="openPreview(arsip, $event.currentTarget)"><strong x-text="arsip.judul"></strong><small x-text="arsip.nomor"></small><span class="category-badge" x-bind:class="categoryClass(arsip.kategori)" x-text="arsip.kategori"></span></button>
                <button type="button" class="favorite-remove" aria-label="Lepas dari favorit" title="Lepas dari favorit" x-on:click="toggleFavorite(arsip)"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="m12 3 2.8 5.7 6.2.9-4.5 4.4 1.1 6.2-5.6-3-5.6 3 1.1-6.2L3 9.6l6.2-.9L12 3Z"/></svg></button>
            </li>
        </template>
    </ul>
    <a class="panel-footer-link" x-show="favoriteArchives.length > 0" x-cloak href="{{ route('pegawai.arsip.index', ['favorit' => 1]) }}">Lihat semua favorit <span aria-hidden="true">&rarr;</span></a>
    <div class="widget-empty widget-empty--small" x-show="favoriteArchives.length === 0"><span class="empty-icon">&#9734;</span><strong>Belum ada arsip favorit</strong><p>Klik ikon bintang pada arsip untuk menyematkannya.</p></div>
</section>
