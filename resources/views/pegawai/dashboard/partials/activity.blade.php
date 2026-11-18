<section class="dashboard-panel lower-panel" aria-labelledby="activity-title">
    <div class="panel-heading">
        <div><span class="section-kicker">AKTIVITAS TERKINI</span><h2 id="activity-title">Riwayat Unduhan / Akses Terakhir</h2></div>
        <a class="panel-footer-link heading-link" href="{{ route('pegawai.arsip.index') }}">Jelajahi arsip <span aria-hidden="true">→</span></a>
    </div>
    @if($riwayat->isNotEmpty())
        <ol class="activity-list">
            @foreach($riwayat as $log)
                @php($activityArchive = $log->subject instanceof \App\Models\Arsip ? $log->subject : null)
                <li class="activity-item">
                    <span class="activity-icon {{ str_contains($log->activity, 'Unduh') ? 'activity-icon--download' : 'activity-icon--preview' }}">
                        @if(str_contains($log->activity, 'Unduh'))
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><path d="m7 10 5 5 5-5M12 15V3"/></svg>
                        @else
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7S2 12 2 12Z"/><circle cx="12" cy="12" r="3"/></svg>
                        @endif
                    </span>
                    <div class="activity-copy">
                        @if($activityArchive)
                            <button type="button" class="text-link" x-on:click="openPreviewFromId({{ $activityArchive->id }}, $event.currentTarget)">{{ $activityArchive->judul }}</button>
                            <small>No. {{ $activityArchive->nomor_surat ?: 'Nomor surat tidak tersedia' }}</small>
                        @else
                            <strong>{{ $log->description }}</strong>
                        @endif
                    </div>
                    <time datetime="{{ $log->created_at->toIso8601String() }}" title="{{ $log->created_at->timezone(config('app.timezone'))->format('d M Y, H:i') }} WIB">{{ $log->created_at->locale('id')->diffForHumans() }}</time>
                </li>
            @endforeach
        </ol>
        <a class="panel-footer-link" href="{{ route('pegawai.arsip.index') }}">Lihat seluruh riwayat <span aria-hidden="true">→</span></a>
    @else
        <div class="activity-empty">
            <span class="empty-icon">▤</span><strong>Belum ada aktivitas</strong>
            <p>Riwayat preview dan unduhan arsip akan tercatat di sini.</p>
            <a class="primary-link-button" href="{{ route('pegawai.arsip.index') }}">Jelajahi Arsip</a>
        </div>
    @endif
</section>
