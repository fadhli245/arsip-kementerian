@extends('layouts.app')

@section('title', 'Dashboard - SIM Arsip')

@section('content')
<style>
    .page-header { margin-bottom: 25px; }
    .page-header h2 { font-family: 'Manrope', sans-serif; color: var(--navy); font-size: 24px; margin: 0; }
    .page-header p { color: var(--muted); margin-top: 5px; font-size: 14px; }
    
    .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 30px; }
    .stat-card { background: white; padding: 25px; border-radius: 14px; box-shadow: 0 5px 20px rgba(22,49,78,.03); border: 1px solid #e2eaf2; display: flex; align-items: center; gap: 15px; }
    .stat-icon { width: 50px; height: 50px; border-radius: 12px; display: flex; justify-content: center; align-items: center; font-size: 24px; }
    .stat-info h3 { margin: 0; font-size: 28px; color: var(--navy); font-family: 'Manrope', sans-serif; }
    .stat-info p { margin: 0; font-size: 13px; color: var(--muted); font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }

    .card-1 .stat-icon { background: #e0f2fe; color: #0284c7; }
    .card-2 .stat-icon { background: #dcfce7; color: #16a34a; }
    .card-3 .stat-icon { background: #fee2e2; color: #dc2626; }
    .card-4 .stat-icon { background: #fef9c3; color: #ca8a04; }

    /* Layout untuk Grafik dan Tabel Terbaru */
    .dashboard-grid { display: grid; grid-template-columns: 1fr 1.5fr; gap: 25px; align-items: start; }
    
    .chart-container { background: white; padding: 30px; border-radius: 14px; box-shadow: 0 5px 20px rgba(22,49,78,.03); border: 1px solid #e2eaf2; text-align: center; }
    .chart-container h3 { color: var(--navy); margin-top: 0; margin-bottom: 20px; font-family: 'Manrope', sans-serif; font-size: 18px; }
    
    .legend-box { display: flex; justify-content: center; flex-wrap: wrap; gap: 15px; margin-top: 20px; }
    .legend-item { display: flex; align-items: center; gap: 8px; font-size: 13px; color: #475569; font-weight: 600; }
    .legend-color { width: 14px; height: 14px; border-radius: 4px; }

    .recent-card { background: white; padding: 30px; border-radius: 14px; box-shadow: 0 5px 20px rgba(22,49,78,.03); border: 1px solid #e2eaf2; }
    .recent-card h3 { color: var(--navy); margin-top: 0; margin-bottom: 20px; font-family: 'Manrope', sans-serif; font-size: 18px; }
    
    table.recent-table { width: 100%; border-collapse: collapse; font-size: 13px; }
    table.recent-table th { background: #f8fafc; color: var(--navy); padding: 10px; border-bottom: 2px solid #e2eaf2; text-align: left; }
    table.recent-table td { padding: 12px 10px; border-bottom: 1px solid #e2eaf2; color: #334155; }
    .badge-kategori-small { background: #e0f2fe; color: #0369a1; padding: 3px 8px; border-radius: 4px; font-size: 11px; font-weight: 600; }
    
    @media (max-width: 992px) {
        .dashboard-grid { grid-template-columns: 1fr; }
    }
</style>

<div class="page-header">
    <h2>👋 Selamat Datang, {{ Auth::user()->nama ?? Auth::user()->name ?? 'Administrator' }}!</h2>
    <p>Berikut adalah ringkasan data arsip di Kementerian Perhubungan Laut saat ini.</p>
</div>

<!-- Grid 4 Kartu Statistik -->
<div class="stats-grid">
    <div class="stat-card card-1">
        <div class="stat-icon">📁</div>
        <div class="stat-info">
            <h3>{{ $totalArsip }}</h3>
            <p>Total Arsip</p>
        </div>
    </div>
    <div class="stat-card card-2">
        <div class="stat-icon">📥</div>
        <div class="stat-info">
            <h3>{{ $suratMasuk }}</h3>
            <p>Surat Masuk</p>
        </div>
    </div>
    <div class="stat-card card-3">
        <div class="stat-icon">📤</div>
        <div class="stat-info">
            <h3>{{ $suratKeluar }}</h3>
            <p>Surat Keluar</p>
        </div>
    </div>
    <div class="stat-card card-4">
        <div class="stat-icon">📜</div>
        <div class="stat-info">
            <h3>{{ $sk + $laporan }}</h3>
            <p>SK & Laporan</p>
        </div>
    </div>
</div>

<!-- Layout Menyamping: Grafik Kiri, Arsip Terbaru Kanan -->
<div class="dashboard-grid">
    
    <!-- Bagian Kiri: Grafik -->
    <div class="chart-container">
        <h3>Grafik Kategori Arsip</h3>
        <div style="position: relative; display: inline-block;">
            <canvas id="customCanvasChart" width="230" height="230"></canvas>
        </div>
        <div class="legend-box">
            <div class="legend-item"><div class="legend-color" style="background: #10b981;"></div> Surat Masuk ({{ $suratMasuk }})</div>
            <div class="legend-item"><div class="legend-color" style="background: #ef4444;"></div> Surat Keluar ({{ $suratKeluar }})</div>
            <div class="legend-item"><div class="legend-color" style="background: #f59e0b;"></div> SK ({{ $sk }})</div>
            <div class="legend-item"><div class="legend-color" style="background: #3b82f6;"></div> Laporan ({{ $laporan }})</div>
        </div>
    </div>

    <!-- Bagian Kanan: Tabel Arsip Terbaru -->
    <div class="recent-card">
        <h3>📄 Arsip Terakhir Diunggah</h3>
        <table class="recent-table">
            <thead>
                <tr>
                    <th>Judul Dokumen</th>
                    <th>Kategori</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($arsipTerbaru ?? [] as $arsip)
                <tr>
                    <td><strong>{{ Str::limit($arsip->judul, 40) }}</strong></td>
                    <td><span class="badge-kategori-small">{{ $arsip->kategori }}</span></td>
                    <td>{{ \Carbon\Carbon::parse($arsip->tanggal_dokumen)->format('d M Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" style="text-align: center; color: #94a3b8; padding: 20px;">Belum ada data arsip.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        <div style="margin-top: 15px; text-align: right;">
            <a href="{{ route('admin.arsip.index') }}" style="color: #0369a1; text-decoration: none; font-size: 13px; font-weight: 600;">Lihat Semua Arsip &rarr;</a>
        </div>
    </div>

</div>

<!-- Script Grafik yang sudah diperbaiki tanda matematikanya -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const canvas = document.getElementById('customCanvasChart');
        if (!canvas) return;
        const ctx = canvas.getContext('2d');
        
        const values = [{{ $suratMasuk }}, {{ $suratKeluar }}, {{ $sk }}, {{ $laporan }}];
        const colors = ['#10b981', '#ef4444', '#f59e0b', '#3b82f6'];
        const total = values.reduce((a, b) => a + b, 0);

        const centerX = canvas.width / 2;
        const centerY = canvas.height / 2;
        const radius = 90;
        const innerRadius = 55;

        ctx.clearRect(0, 0, canvas.width, canvas.height);

        if (total === 0) {
            ctx.beginPath();
            ctx.arc(centerX, centerY, radius, 0, 2 * Math.PI);
            ctx.arc(centerX, centerY, innerRadius, 2 * Math.PI, 0, true);
            ctx.fillStyle = '#e2eaf2';
            ctx.fill();
            
            ctx.fillStyle = '#94a3b8';
            ctx.font = '14px Manrope, sans-serif';
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            ctx.fillText('Belum ada data', centerX, centerY);
            return;
        }

        let startAngle = -0.5 * Math.PI;

        values.forEach((val, index) => {
            if (val === 0) return;
            const sliceAngle = (val / total) * 2 * Math.PI;
            const endAngle = startAngle + sliceAngle;

            ctx.beginPath();
            ctx.arc(centerX, centerY, radius, startAngle, endAngle);
            ctx.arc(centerX, centerY, innerRadius, endAngle, startAngle, true);
            ctx.closePath();

            ctx.fillStyle = colors[index];
            ctx.fill();

            startAngle = endAngle;
        });

        // Teks Total di tengah ring
        ctx.fillStyle = '#16314e';
        ctx.font = 'bold 24px Manrope, sans-serif';
        ctx.textAlign = 'center';
        ctx.textBaseline = 'middle';
        ctx.fillText(total, centerX, centerY - 6);

        ctx.fillStyle = '#64748b';
        ctx.font = '11px Manrope, sans-serif';
        ctx.fillText('TOTAL ARSIP', centerX, centerY + 14);
    });
</script>
@endsection