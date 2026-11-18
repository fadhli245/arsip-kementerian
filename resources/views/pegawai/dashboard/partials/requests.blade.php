<section class="dashboard-panel lower-panel request-panel" aria-labelledby="requests-title">
    <div class="panel-heading">
        <div><span class="section-kicker">LAYANAN ARSIP</span><h2 id="requests-title">Status Pengajuan / Permohonan Arsip</h2></div>
        <span class="count-badge">{{ $totalPermohonan }}</span>
    </div>
    @if($permohonan->isNotEmpty())
        <ul class="request-list">
            @foreach($permohonan as $item)
                @php($statusClass = ['Menunggu' => 'status-waiting', 'Diproses' => 'status-processing', 'Disetujui' => 'status-approved', 'Ditolak' => 'status-rejected'][$item->status] ?? 'status-waiting')
                <li class="request-item" x-data="{ open: false }">
                    <div class="request-item-main">
                        <div><strong>{{ $item->judul_arsip }}</strong><small>{{ $item->kategori ?: 'Kategori belum ditentukan' }} · {{ $item->created_at->locale('id')->diffForHumans() }}</small></div>
                        <span class="request-status {{ $statusClass }}">{{ $item->status }}</span>
                    </div>
                    @if($item->status === 'Ditolak' && $item->alasan_penolakan)
                        <button class="reason-toggle" type="button" x-on:click="open = !open" x-bind:aria-expanded="open">Lihat alasan penolakan</button>
                        <p class="rejection-reason" x-show="open" x-transition>{{ $item->alasan_penolakan }}</p>
                    @elseif($item->status === 'Disetujui')
                        <a class="reason-toggle" href="{{ route('pegawai.arsip.index', $item->referensi ? ['q' => $item->referensi] : []) }}">Lihat arsip</a>
                    @endif
                </li>
            @endforeach
        </ul>
    @else
        <div class="request-empty">Belum ada permohonan arsip.</div>
    @endif

    <form class="request-form" action="{{ route('pegawai.permohonan.store') }}" method="POST" x-on:submit="requestSubmitting = true">
        @csrf
        <div class="form-heading"><h3>Ajukan Permohonan</h3><p>Sampaikan kebutuhan dokumen kedinasan Anda.</p></div>
        <label for="judul_arsip">Judul / Perihal Arsip yang Dibutuhkan</label>
        <input id="judul_arsip" type="text" name="judul_arsip" maxlength="200" required value="{{ old('judul_arsip') }}" x-on:input="requestTitleLength = $event.target.value.length" placeholder="Contoh: Laporan kegiatan koordinasi">
        <small class="field-counter"><span x-text="requestTitleLength">{{ mb_strlen(old('judul_arsip', '')) }}</span>/200 karakter</small>
        @error('judul_arsip')<span class="field-error">{{ $message }}</span>@enderror
        <div class="request-fields-row">
            <div>
                <label for="request-category">Kategori Dokumen</label>
                <select id="request-category" name="kategori" required>
                    <option value="">Pilih kategori</option>
                    @foreach($kategoriList as $kategori)
                        <option value="{{ $kategori }}" {{ old('kategori') === $kategori ? 'selected' : '' }}>{{ $kategori }}</option>
                    @endforeach
                </select>
                @error('kategori')<span class="field-error">{{ $message }}</span>@enderror
            </div>
            <div>
                <label for="referensi">Perkiraan Tahun / Nomor Surat <span>(opsional)</span></label>
                <input id="referensi" type="text" name="referensi" maxlength="100" value="{{ old('referensi') }}" placeholder="Mis. 2026 / 124-HUBLA">
                @error('referensi')<span class="field-error">{{ $message }}</span>@enderror
            </div>
        </div>
        <label for="request-purpose">Keperluan Permohonan</label>
        <textarea id="request-purpose" name="keterangan" rows="3" maxlength="500" required x-on:input="requestPurposeLength = $event.target.value.length" placeholder="Jelaskan keperluan penggunaan arsip ini...">{{ old('keterangan') }}</textarea>
        <small class="field-counter"><span x-text="requestPurposeLength">{{ mb_strlen(old('keterangan', '')) }}</span>/500 karakter</small>
        @error('keterangan')<span class="field-error">{{ $message }}</span>@enderror
        <button class="request-submit" type="submit" x-bind:disabled="requestSubmitting"><span class="request-spinner" x-show="requestSubmitting"></span><span x-text="requestSubmitting ? 'Mengirim permohonan…' : 'Ajukan Permohonan'">Ajukan Permohonan</span></button>
    </form>
</section>
