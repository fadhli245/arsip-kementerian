@extends('layouts.app')

@section('title', 'Kelola Arsip - SIM Arsip')

@section('content')
<style>
    .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; }
    .page-header h2 { font-family: 'Manrope', sans-serif; color: var(--navy); font-size: 22px; }
    .btn-add { background: var(--blue); color: white; padding: 10px 18px; border-radius: 8px; text-decoration: none; font-weight: 700; font-size: 13px; transition: 0.2s; }
    .btn-add:hover { filter: brightness(1.1); }
    
    /* Styling Filter & Search Box */
    .filter-card { background: white; border: 1px solid #e2eaf2; border-radius: 12px; padding: 15px 20px; margin-bottom: 20px; box-shadow: 0 4px 15px rgba(22,49,78,.02); }
    .filter-form { display: flex; gap: 12px; align-items: center; flex-wrap: wrap; }
    .filter-input { padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13px; font-family: inherit; outline: none; flex: 1; min-width: 200px; }
    .filter-select { padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13px; font-family: inherit; outline: none; background: white; }
    .btn-search { background: #0369a1; color: white; border: none; padding: 8px 16px; border-radius: 8px; font-weight: 600; font-size: 13px; cursor: pointer; }
    .btn-reset { background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; padding: 8px 14px; border-radius: 8px; text-decoration: none; font-size: 13px; font-weight: 600; }

    .table-card { background: white; border: 1px solid #e2eaf2; border-radius: 14px; padding: 20px; box-shadow: 0 5px 20px rgba(22,49,78,.03); overflow-x: auto; }
    table { width: 100%; border-collapse: collapse; text-align: left; font-size: 14px; }
    th { background: #f8fafc; color: var(--navy); padding: 12px; border-bottom: 2px solid #e2eaf2; font-size: 12px; letter-spacing: 0.5px; }
    td { padding: 12px; border-bottom: 1px solid #e2eaf2; color: #334155; vertical-align: middle; }
    .badge-kategori { background: #e0f2fe; color: #0369a1; padding: 4px 8px; border-radius: 6px; font-size: 12px; font-weight: 600; }
    .alert-success { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-size: 13px; }
</style>

<div class="page-header">
    <h2>Kelola Dokumen Arsip</h2>
    <a href="{{ route('admin.arsip.create') }}" class="btn-add">➕ Tambah Arsip Baru</a>
</div>

@if(session('success'))
    <div class="alert-success">
        {{ session('success') }}
    </div>
@endif

<!-- Kotak Pencarian & Filter Kategori -->
<div class="filter-card">
    <form action="{{ route('admin.arsip.index') }}" method="GET" class="filter-form">
        <input type="text" name="search" class="filter-input" placeholder="🔍 Cari judul dokumen atau surat..." value="{{ $search ?? '' }}">
        
        <select name="kategori" class="filter-select">
            <option value="">-- Semua Kategori --</option>
            <option value="Surat Masuk" {{ (isset($kategori) && $kategori == 'Surat Masuk') ? 'selected' : '' }}>Surat Masuk</option>
            <option value="Surat Keluar" {{ (isset($kategori) && $kategori == 'Surat Keluar') ? 'selected' : '' }}>Surat Keluar</option>
            <option value="Surat Keputusan (SK)" {{ (isset($kategori) && $kategori == 'Surat Keputusan (SK)') ? 'selected' : '' }}>Surat Keputusan (SK)</option>
            <option value="Laporan Kegiatan" {{ (isset($kategori) && $kategori == 'Laporan Kegiatan') ? 'selected' : '' }}>Laporan Kegiatan</option>
        </select>

        <button type="submit" class="btn-search">Cari & Filter</button>
        
        @if(!empty($search) || !empty($kategori))
            <a href="{{ route('admin.arsip.index') }}" class="btn-reset">Reset</a>
        @endif
    </form>
</div>

<div class="table-card">
    <table>
        <thead>
            <tr>
                <th>NO</th>
                <th>JUDUL ARSIP</th>
                <th>KATEGORI</th>
                <th>TANGGAL DOKUMEN</th>
                <th>PENGUNGGAH</th>
                <th>AKSI</th>
            </tr>
        </thead>
        <tbody>
            @forelse($arsips as $index => $arsip)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td><strong>{{ $arsip->judul }}</strong></td>
                    <td><span class="badge-kategori">{{ $arsip->kategori }}</span></td>
                    <td>{{ \Carbon\Carbon::parse($arsip->tanggal_dokumen)->format('d/m/Y') }}</td>
                    <td>{{ $arsip->user->nama ?? $arsip->user->name ?? 'Admin' }}</td>
                    <td>
                        <div style="display: flex; gap: 6px; align-items: center;">
                            <a href="{{ asset('storage/' . $arsip->file_path) }}" target="_blank" style="background: var(--blue); color: white; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-size: 12px; font-weight: 600;">Lihat</a>
                            <a href="{{ route('admin.arsip.edit', $arsip->id) }}" style="background: #eab308; color: white; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-size: 12px; font-weight: 600;">Edit</a>
                            <form action="{{ route('admin.arsip.destroy', $arsip->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus arsip ini?')" style="margin: 0;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background: #ef4444; color: white; border: none; padding: 6px 12px; border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer;">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; color: var(--muted); padding: 30px;">Tidak ada data arsip yang ditemukan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection