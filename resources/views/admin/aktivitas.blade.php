@extends('layouts.app')

@section('title', 'Riwayat Aktivitas - SIM Arsip')

@section('content')
<style>
    .page-header { margin-bottom: 25px; }
    .page-header h2 { font-family: 'Manrope', sans-serif; color: var(--navy); font-size: 24px; margin: 0; }
    .page-header p { color: var(--muted); margin-top: 5px; font-size: 14px; }
    
    .table-card { background: white; border: 1px solid #e2eaf2; border-radius: 14px; padding: 25px; box-shadow: 0 5px 20px rgba(22,49,78,.03); }
    table { width: 100%; border-collapse: collapse; text-align: left; font-size: 14px; }
    th { background: #f8fafc; color: var(--navy); padding: 12px; border-bottom: 2px solid #e2eaf2; font-size: 12px; letter-spacing: 0.5px; }
    td { padding: 12px; border-bottom: 1px solid #e2eaf2; color: #334155; vertical-align: middle; }
    .badge-aksi { background: #dcfce7; color: #15803d; padding: 4px 8px; border-radius: 6px; font-size: 12px; font-weight: 600; }
</style>

<div class="page-header">
    <h2>🕒 Riwayat Aktivitas Sistem</h2>
    <p>Catatan log aktivitas penambahan dan pengelolaan arsip dokumen.</p>
</div>

<div class="table-card">
    <table>
        <thead>
            <tr>
                <th>WAKTU</th>
                <th>PENGGUNA</th>
                <th>AKTIVITAS / KETERANGAN</th>
                <th>KATEGORI</th>
            </tr>
        </thead>
        <tbody>
            @forelse($riwayats as $item)
            <tr>
                <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                <td><strong>{{ $item->user->name ?? 'Administrator' }}</strong></td>
                <td>Menambahkan dokumen arsip: <em>{{ $item->judul }}</em></td>
                <td><span class="badge-aksi">{{ $item->kategori }}</span></td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="text-align: center; color: var(--muted); padding: 30px;">Belum ada riwayat aktivitas tercatat.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    <div style="margin-top: 20px;">
        {{ $riwayats->links() }}
    </div>
</div>
@endsection