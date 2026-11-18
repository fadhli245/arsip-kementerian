import Alpine from 'alpinejs';
import Chart from 'chart.js/auto';

window.Alpine = Alpine;

const chartTextColor = () => document.documentElement.classList.contains('dark') ? '#dbe7f5' : '#475569';
const chartGridColor = () => document.documentElement.classList.contains('dark') ? 'rgba(148,163,184,.18)' : 'rgba(148,163,184,.25)';

Alpine.data('notificationMenu', () => ({
    open: false,
    unread: 0,
    init() { this.unread = Number(this.$el.dataset.unread || 0); },
    async markAllRead() {
        if (!this.unread) return;
        try {
            const response = await fetch(window.adminDashboardData.notificationUrl, {
                method: 'POST', credentials: 'same-origin',
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': window.adminDashboardData.csrf },
            });
            if (!response.ok) throw new Error('Notifikasi gagal diperbarui. Muat ulang halaman dan coba lagi.');
            this.unread = 0;
            document.querySelectorAll('.admin-notification-item').forEach((item) => {
                item.classList.add('is-read');
                item.querySelector('i[aria-label="Belum dibaca"]')?.remove();
            });
        } catch (error) {
            window.dispatchEvent(new CustomEvent('dashboard-toast', { detail: error.message || 'Terjadi gangguan jaringan.' }));
        }
    },
}));

Alpine.data('adminDashboard', () => ({
    year: String(window.adminDashboardData.selectedYear),
    chart: window.adminDashboardData.chart,
    chartInstance: null,
    chartLoading: false,
    chartError: '',
    toast: '',
    toastTimer: null,
    get chartTotal() { return (this.chart?.data || []).reduce((total, value) => total + Number(value || 0), 0); },
    get peakMonth() {
        const peak = Math.max(...(this.chart?.data || [0]));
        if (!peak) return '—';
        return `${this.chart.labels[(this.chart.data || []).indexOf(peak)]} (${peak})`;
    },
    init() {
        this.$nextTick(() => this.renderChart());
        window.addEventListener('dashboard-toast', (event) => this.showToast(event.detail));
        new MutationObserver(() => this.renderChart()).observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
    },
    async loadChart() {
        this.chartLoading = true;
        this.chartError = '';
        try {
            const url = new URL(window.adminDashboardData.chartUrl, window.location.origin);
            url.searchParams.set('tahun', this.year);
            const response = await fetch(url, { headers: { 'Accept': 'application/json' }, credentials: 'same-origin' });
            if (!response.ok) throw new Error(response.status === 422 ? 'Tahun yang dipilih tidak tersedia.' : 'Grafik gagal dimuat. Periksa koneksi lalu coba lagi.');
            this.chart = await response.json();
            this.$nextTick(() => this.renderChart());
        } catch (error) {
            this.chartError = error.message || 'Grafik gagal dimuat.';
        } finally {
            this.chartLoading = false;
        }
    },
    renderChart() {
        const canvas = document.getElementById('adminUploadsChart');
        if (!canvas || this.chartTotal === 0) return;
        this.chartInstance?.destroy();
        this.chartInstance = new Chart(canvas, {
            type: 'bar',
            data: { labels: this.chart.labels, datasets: [{ data: this.chart.data, backgroundColor: '#2563eb', hoverBackgroundColor: '#1d4ed8', borderRadius: 7, borderSkipped: false, maxBarThickness: 38 }] },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { display: false }, tooltip: { callbacks: { label: (context) => `${context.label} ${this.year}: ${context.raw} dokumen` } } },
                scales: {
                    x: { grid: { display: false }, border: { display: false }, ticks: { color: chartTextColor() } },
                    y: { beginAtZero: true, ticks: { precision: 0, stepSize: 1, color: chartTextColor() }, grid: { color: chartGridColor(), borderDash: [4, 4] }, border: { display: false } },
                },
            },
        });
    },
    showToast(message) {
        this.toast = message;
        clearTimeout(this.toastTimer);
        this.toastTimer = setTimeout(() => { this.toast = ''; }, 4500);
    },
}));

Alpine.start();
