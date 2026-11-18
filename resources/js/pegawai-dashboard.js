import Alpine from 'alpinejs';
import Chart from 'chart.js/auto';

window.Alpine = Alpine;

const categoryPalette = {
    'Laporan Kegiatan': '#2563eb',
    'Surat Masuk': '#16a34a',
    'Surat Keputusan (SK)': '#f59e0b',
    'Surat Keluar': '#64748b',
};

const dashboardTheme = () => document.documentElement.classList.contains('dark') ? 'dark' : 'light';
const themeTextColor = () => dashboardTheme() === 'dark' ? '#dbe7f5' : '#334155';

Alpine.data('notificationMenu', () => ({
    open: false,
    unread: 0,
    init() { this.unread = Number(this.$el.dataset.unread || 0); },
    async markAllRead() {
        if (this.unread < 1) return;
        try {
            const response = await fetch(window.pegawaiDashboardData?.notificationUrl, {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                },
            });
            if (!response.ok) throw new Error('Notifikasi tidak dapat diperbarui.');
            this.unread = 0;
            this.open = false;
            document.querySelectorAll('.notification-item .unread-dot').forEach((dot) => dot.remove());
            document.querySelectorAll('.notification-item').forEach((item) => item.classList.add('is-read'));
        } catch (_) {
            window.dispatchEvent(new CustomEvent('dashboard-toast', { detail: 'Gagal menandai notifikasi. Coba lagi.' }));
        }
    },
}));

Alpine.data('pegawaiDashboard', () => ({
    kategori: '',
    tahun: '',
    greeting: '',
    today: '',
    searchQuery: '',
    searchError: '',
    requestSubmitting: false,
    requestTitleLength: 0,
    requestPurposeLength: 0,
    archives: [],
    historyArchives: [],
    favoriteArchives: [],
    favoriteIds: [],
    distribution: [],
    chartLegend: [],
    chart: null,
    toast: { visible: false, message: '' },
    toastTimer: null,
    preview: { open: false, archive: null, url: '', loading: false },
    previousFocus: null,

    init() {
        const data = window.pegawaiDashboardData || {};
        this.archives = data.archives || [];
        this.historyArchives = data.history || [];
        this.favoriteArchives = data.favorites || [];
        this.favoriteIds = (data.favoriteIds || []).map(Number);
        this.distribution = data.distribution || [];
        this.kategori = data.selectedCategory || '';
        this.tahun = data.selectedYear || '';
        this.updateClock();
        window.setInterval(() => this.updateClock(), 60000);

        const title = document.querySelector('#judul_arsip');
        const purpose = document.querySelector('#request-purpose');
        this.requestTitleLength = title?.value.length || 0;
        this.requestPurposeLength = purpose?.value.length || 0;

        this.$nextTick(() => this.createChart());
        document.addEventListener('keydown', (event) => {
            if (event.key !== '/' || event.altKey || event.ctrlKey || event.metaKey) return;
            const target = event.target;
            if (target instanceof HTMLElement && (target.isContentEditable || ['INPUT', 'TEXTAREA', 'SELECT'].includes(target.tagName))) return;
            event.preventDefault();
            document.querySelector('#dashboard-search')?.focus();
        });
        window.addEventListener('dashboard-toast', (event) => this.showToast(event.detail));
        this.themeObserver = new MutationObserver(() => this.$nextTick(() => this.updateChartTheme()));
        this.themeObserver.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
    },

    updateClock() {
        const now = new Date();
        const hour = now.getHours();
        this.greeting = hour < 11 ? 'Selamat Pagi' : (hour < 15 ? 'Selamat Siang' : (hour < 18 ? 'Selamat Sore' : 'Selamat Malam'));
        this.today = new Intl.DateTimeFormat('id-ID', { weekday: 'long', day: '2-digit', month: 'long', year: 'numeric' }).format(now);
    },

    animateCount(element, target) {
        const end = Number(target) || 0;
        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            element.textContent = new Intl.NumberFormat('id-ID').format(end);
            return;
        }
        const start = performance.now();
        const duration = 600;
        const frame = (time) => {
            const progress = Math.min((time - start) / duration, 1);
            const eased = 1 - Math.pow(1 - progress, 3);
            element.textContent = new Intl.NumberFormat('id-ID').format(Math.round(end * eased));
            if (progress < 1) window.requestAnimationFrame(frame);
        };
        window.requestAnimationFrame(frame);
    },

    filteredArchives() {
        return this.archives.filter((item) =>
            (!this.kategori || item.kategori === this.kategori) &&
            (!this.tahun || item.tahun === String(this.tahun))
        );
    },

    categoryClass(name) {
        if (name === 'Laporan Kegiatan') return 'category-laporan-kegiatan';
        if (name === 'Surat Masuk') return 'category-surat-masuk';
        if (name === 'Surat Keputusan (SK)') return 'category-surat-keputusan';
        if (name === 'Surat Keluar') return 'category-surat-keluar';
        return '';
    },

    submitSearch(event) {
        const value = (this.searchQuery || '').trim();
        if (value.length < 2) {
            this.searchError = 'Masukkan minimal 2 karakter untuk mencari arsip.';
            return;
        }
        this.searchError = '';
        event.target.submit();
    },

    quickSearch(value) {
        this.searchQuery = value;
        this.$nextTick(() => document.querySelector('.hero-search')?.requestSubmit());
    },

    createChart() {
        const canvas = this.$refs.categoryChart;
        if (!canvas || this.distribution.length === 0) return;
        const colors = this.distribution.map((item) => categoryPalette[item.label] || '#64748b');
        this.chartLegend = this.distribution.map((item, index) => ({ ...item, color: colors[index], hidden: false }));
        this.chart = new Chart(canvas, {
            type: 'doughnut',
            data: {
                labels: this.distribution.map((item) => item.label),
                datasets: [{ data: this.distribution.map((item) => item.value), backgroundColor: colors, borderColor: dashboardTheme() === 'dark' ? '#111c2e' : '#fff', borderWidth: 3, hoverOffset: 5 }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: (context) => {
                                const total = context.dataset.data.reduce((sum, value) => sum + value, 0);
                                const percent = total ? Math.round((context.raw / total) * 100) : 0;
                                return context.label + ': ' + context.raw + ' dokumen (' + percent + '%)';
                            },
                        },
                    },
                },
            },
        });
    },

    percentage(value) {
        const total = this.distribution.reduce((sum, item) => sum + item.value, 0);
        return total ? Math.round((value / total) * 100) : 0;
    },

    toggleChartCategory(index) {
        this.chart?.toggleDataVisibility(index);
        this.chart?.update();
        if (this.chartLegend[index]) this.chartLegend[index].hidden = !this.chartLegend[index].hidden;
    },

    updateChartTheme() {
        if (!this.chart) return;
        this.chart.data.datasets[0].borderColor = dashboardTheme() === 'dark' ? '#111c2e' : '#fff';
        this.chart.options.plugins.tooltip.titleColor = themeTextColor();
        this.chart.options.plugins.tooltip.bodyColor = themeTextColor();
        this.chart.options.plugins.tooltip.backgroundColor = dashboardTheme() === 'dark' ? '#17263b' : '#0f294a';
        this.chart.update();
    },

    async toggleFavorite(archive) {
        const wasFavorite = this.favoriteIds.includes(Number(archive.id));
        const method = wasFavorite ? 'DELETE' : 'POST';
        const url = '/pegawai/arsip/' + archive.id + '/favorit';
        if (wasFavorite) {
            this.favoriteIds = this.favoriteIds.filter((id) => id !== Number(archive.id));
            this.favoriteArchives = this.favoriteArchives.filter((item) => Number(item.id) !== Number(archive.id));
        } else {
            this.favoriteIds = [...this.favoriteIds, Number(archive.id)];
            archive.favorit = true;
            this.favoriteArchives = [archive, ...this.favoriteArchives.filter((item) => Number(item.id) !== Number(archive.id))].slice(0, 4);
        }

        try {
            const response = await fetch(url, {
                method,
                credentials: 'same-origin',
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '' },
            });
            if (!response.ok) throw new Error('Favorit gagal disimpan.');
            this.showToast((await response.json()).message);
        } catch (_) {
            if (wasFavorite) {
                this.favoriteIds = [...this.favoriteIds, Number(archive.id)];
                this.favoriteArchives = [archive, ...this.favoriteArchives].slice(0, 4);
            } else {
                this.favoriteIds = this.favoriteIds.filter((id) => id !== Number(archive.id));
                this.favoriteArchives = this.favoriteArchives.filter((item) => Number(item.id) !== Number(archive.id));
            }
            this.showToast('Favorit tidak dapat diperbarui. Perubahan dibatalkan.');
        }
    },

    openPreview(archive, trigger) {
        this.previousFocus = trigger || document.activeElement;
        this.preview.archive = archive;
        this.preview.url = archive.previewUrl;
        this.preview.loading = archive.format === 'PDF';
        this.preview.open = true;
        document.body.dataset.previousOverflow = document.body.style.overflow;
        document.body.style.overflow = 'hidden';
        this.$nextTick(() => this.$refs.previewDialog?.focus());
        fetch(archive.previewLogUrl, {
            method: 'POST',
            credentials: 'same-origin',
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '' },
        }).then((response) => {
            if (!response.ok) throw new Error('Aktivitas pratinjau gagal dicatat.');
        }).catch(() => this.showToast('Pratinjau terbuka, tetapi aktivitas belum tercatat.'));
    },

    openPreviewFromId(id, trigger) {
        const archive = this.archives.find((item) => Number(item.id) === Number(id)) || this.favoriteArchives.find((item) => Number(item.id) === Number(id)) || this.historyArchives.find((item) => Number(item.id) === Number(id));
        if (archive) this.openPreview(archive, trigger);
        else this.showToast('Detail arsip tidak tersedia pada halaman ini. Gunakan Cari & Unduh Arsip.');
    },

    closePreview() {
        if (!this.preview.open) return;
        this.preview.open = false;
        this.preview.url = '';
        this.preview.archive = null;
        this.preview.loading = false;
        document.body.style.overflow = document.body.dataset.previousOverflow || '';
        this.$nextTick(() => this.previousFocus?.focus?.());
    },

    trapFocus(event) {
        if (event.key !== 'Tab') return;
        const items = [...this.$refs.previewDialog.querySelectorAll('a[href], button:not([disabled]), iframe, [tabindex]:not([tabindex="-1"])')];
        if (!items.length) return;
        const first = items[0];
        const last = items[items.length - 1];
        if (event.shiftKey && document.activeElement === first) {
            event.preventDefault();
            last.focus();
        } else if (!event.shiftKey && document.activeElement === last) {
            event.preventDefault();
            first.focus();
        }
    },

    showToast(message) {
        this.toast.message = message;
        this.toast.visible = true;
        window.clearTimeout(this.toastTimer);
        this.toastTimer = window.setTimeout(() => { this.toast.visible = false; }, 3200);
    },
}));

window.togglePegawaiTheme = () => {
    const nextTheme = dashboardTheme() === 'dark' ? 'light' : 'dark';
    document.documentElement.classList.toggle('dark', nextTheme === 'dark');
    try { localStorage.setItem('arsip-theme', nextTheme); } catch (_) {}
    window.dispatchEvent(new CustomEvent('pegawai-theme-changed', { detail: nextTheme }));
};

Alpine.start();
