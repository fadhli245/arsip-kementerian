<section class="dashboard-panel widget-panel chart-panel" aria-labelledby="category-chart-title">
    <div class="panel-heading compact-heading">
        <div><span class="section-kicker">RINGKASAN KOLEKSI</span><h2 id="category-chart-title">Distribusi Kategori Arsip</h2></div>
    </div>
    @if($totalArsip > 0)
        <div class="chart-wrap">
            <canvas x-ref="categoryChart" aria-label="Grafik donat distribusi kategori arsip" role="img"></canvas>
            <div class="chart-center" aria-hidden="true"><strong>{{ number_format($totalArsip, 0, ',', '.') }}</strong><span>dokumen</span></div>
        </div>
        <div class="chart-legend" role="group" aria-label="Filter kategori pada grafik">
            <template x-for="(item, index) in chartLegend" :key="item.label">
                <button type="button" class="legend-item" x-bind:class="{ 'legend-hidden': item.hidden }" x-on:click="toggleChartCategory(index)" x-bind:aria-pressed="!item.hidden">
                    <i x-bind:style="'--legend-color:' + item.color"></i><span x-text="item.label"></span>
                    <strong x-text="item.value"></strong><small x-text="percentage(item.value) + '%'"></small>
                </button>
            </template>
        </div>
        <table class="sr-only"><caption>Ringkasan distribusi kategori arsip</caption><thead><tr><th>Kategori</th><th>Jumlah</th></tr></thead>
            <tbody>@foreach($distribusiKategori as $item)<tr><th>{{ $item['label'] }}</th><td>{{ $item['value'] }}</td></tr>@endforeach</tbody>
        </table>
    @else
        <div class="widget-empty"><span class="empty-icon">◔</span><strong>Belum ada data kategori</strong><p>Grafik akan tampil setelah arsip tersedia.</p></div>
    @endif
</section>
