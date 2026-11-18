<div class="preview-overlay" x-show="preview.open" x-transition.opacity x-cloak x-on:click.self="closePreview()" x-on:keydown.escape.window="closePreview()">
    <section class="preview-dialog" role="dialog" aria-modal="true" aria-labelledby="preview-title" tabindex="-1" x-ref="previewDialog" x-on:keydown.tab="trapFocus($event)">
        <header class="preview-header">
            <div><span class="section-kicker">PRATINJAU DOKUMEN</span><h2 id="preview-title" x-text="preview.archive?.judul || 'Pratinjau Arsip'"></h2></div>
            <button type="button" class="modal-close" aria-label="Tutup pratinjau" x-on:click="closePreview()">×</button>
        </header>
        <div class="preview-content">
            <div class="preview-document">
                <div class="preview-loading" x-show="preview.loading"><span class="loading-skeleton"></span><span class="loading-skeleton"></span><span class="loading-skeleton"></span></div>
                <template x-if="preview.archive && preview.archive.format === 'PDF'">
                    <iframe x-ref="previewFrame" x-bind:src="preview.url" title="Pratinjau dokumen PDF" x-on:load="preview.loading = false"></iframe>
                </template>
                <div class="preview-unavailable" x-show="preview.archive && preview.archive.format !== 'PDF'">
                    <span class="empty-icon">▤</span><strong>Pratinjau hanya tersedia untuk dokumen PDF.</strong><p>Silakan unduh dokumen untuk membukanya.</p>
                </div>
            </div>
            <aside class="preview-metadata">
                <span class="section-kicker">INFORMASI ARSIP</span>
                <dl>
                    <div><dt>Judul</dt><dd x-text="preview.archive?.judul || '-'"></dd></div>
                    <div><dt>Nomor Dokumen</dt><dd x-text="preview.archive?.nomor || '-'"></dd></div>
                    <div><dt>Kategori</dt><dd><span class="category-badge" x-bind:class="categoryClass(preview.archive?.kategori)" x-text="preview.archive?.kategori || '-'"></span></dd></div>
                    <div><dt>Tingkat Akses</dt><dd x-text="preview.archive?.akses || '-'"></dd></div>
                    <div><dt>Tanggal Unggah</dt><dd x-text="preview.archive?.tanggalUnggah || '-'"></dd></div>
                    <div><dt>Unit Pengunggah</dt><dd x-text="preview.archive?.unit || '-'"></dd></div>
                    <div><dt>Ukuran Berkas</dt><dd x-text="preview.archive?.ukuran || '-'"></dd></div>
                </dl>
                <div class="preview-actions">
                    <button type="button" class="preview-favorite" x-on:click="preview.archive && toggleFavorite(preview.archive)" x-bind:aria-pressed="preview.archive && favoriteIds.includes(preview.archive.id)">☆ Favorit</button>
                    <a class="preview-download" x-bind:href="preview.archive?.downloadUrl || '#'" x-bind:aria-disabled="!preview.archive">Unduh Dokumen</a>
                    <button type="button" class="preview-close-button" x-on:click="closePreview()">Tutup</button>
                </div>
            </aside>
        </div>
    </section>
</div>
