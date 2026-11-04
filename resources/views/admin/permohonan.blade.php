@extends('layouts.app')

@section('title', 'Persetujuan Permohonan Arsip - SIM Arsip')

@section('content')
<style>
    .approval-page { display:flex; flex-direction:column; gap:16px; }
    .approval-head,.approval-card { background:#fff; border:1px solid #E2E8F0; border-radius:13px; padding:20px; }
    .approval-head h1 { color:#0A2558; font-size:22px; margin-bottom:6px; }
    .approval-head p { color:#64748B; font-size:13px; }
    .approval-table { width:100%; border-collapse:collapse; font-size:13px; }
    .approval-table th,.approval-table td { padding:12px 10px; border-bottom:1px solid #E2E8F0; text-align:left; vertical-align:top; }
    .approval-table th { background:#F8FAFC; color:#475569; white-space:nowrap; }
    .approval-actions { display:flex; flex-wrap:wrap; gap:6px; }
    .approval-actions select,.approval-actions button { padding:7px 9px; border:1px solid #CBD5E1; border-radius:7px; font-size:11px; }
    .approval-actions button { background:#0A2558; color:white; font-weight:700; cursor:pointer; }
    .approval-empty { text-align:center!important; padding:28px!important; color:#64748B; }
</style>
<div class="approval-page">
    <header class="approval-head">
        <h1>Verifikasi &amp; Persetujuan Permohonan Arsip</h1>
        <p>Tinjau kebutuhan dokumen yang diajukan pegawai dan perbarui status prosesnya.</p>
    </header>
    <section class="approval-card" style="overflow:auto">
        <h2 style="color:#0A2558;font-size:16px;margin:0 0 14px">Draft Arsip Menunggu Publikasi</h2>
        <table class="approval-table">
            <thead><tr><th>Dokumen</th><th>Nomor / Kategori</th><th>Pengunggah</th><th>Kerahasiaan</th><th>Status</th><th>Verifikasi</th></tr></thead>
            <tbody>
            @forelse($arsipMenunggu as $arsip)
                <tr>
                    <td><strong>{{ $arsip->judul }}</strong></td>
                    <td>{{ $arsip->nomor_surat ?: '—' }}<br>{{ $arsip->kategori }}</td>
                    <td>{{ $arsip->user->name ?? 'Pengguna tidak tersedia' }}</td>
                    <td>{{ $arsip->classification }}</td>
                    <td>{{ $arsip->approval_status }}</td>
                    <td>
                        <form class="approval-actions" action="{{ route('admin.arsip.approval', $arsip) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <select name="approval_status" aria-label="Status publikasi arsip">
                                @foreach(['Disetujui','Ditolak','Draft','Menunggu'] as $status)
                                    <option value="{{ $status }}" {{ $arsip->approval_status === $status ? 'selected' : '' }}>{{ $status }}</option>
                                @endforeach
                            </select>
                            <button type="submit">Simpan</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td class="approval-empty" colspan="6">Tidak ada draft yang menunggu publikasi.</td></tr>
            @endforelse
            </tbody>
        </table>
        <div style="margin-top:14px">{{ $arsipMenunggu->links() }}</div>
    </section>
    <section class="approval-card" style="overflow:auto">
        <h2 style="color:#0A2558;font-size:16px;margin:0 0 14px">Permohonan Dokumen dari Pegawai</h2>
        <table class="approval-table">
            <thead><tr><th>Pemohon</th><th>Arsip yang diminta</th><th>Keterangan</th><th>Status</th><th>Tanggal</th><th>Perbarui status</th></tr></thead>
            <tbody>
            @forelse($permohonan as $item)
                <tr>
                    <td>{{ $item->user->name }}<br><span style="color:#64748B">{{ $item->user->email }}</span></td>
                    <td><strong>{{ $item->judul_arsip }}</strong></td>
                    <td>{{ $item->keterangan ?: '—' }}</td>
                    <td>{{ $item->status }}</td>
                    <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <form class="approval-actions" action="{{ route('admin.permohonan.update', $item) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <select name="status" aria-label="Status permohonan">
                                @foreach(['Menunggu', 'Diproses', 'Disetujui', 'Ditolak'] as $status)
                                    <option value="{{ $status }}" {{ $item->status === $status ? 'selected' : '' }}>{{ $status }}</option>
                                @endforeach
                            </select>
                            <button type="submit">Simpan</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td class="approval-empty" colspan="6">Belum ada permohonan arsip dari pegawai.</td></tr>
            @endforelse
            </tbody>
        </table>
        <div style="margin-top:16px">{{ $permohonan->links() }}</div>
    </section>
</div>
@endsection
